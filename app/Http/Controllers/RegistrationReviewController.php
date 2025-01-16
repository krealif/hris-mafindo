<?php

namespace App\Http\Controllers;

use App\Enums\RegistrationBaruStepEnum;
use App\Enums\RegistrationLamaStepEnum;
use App\Enums\RegistrationStatusEnum;
use App\Enums\RegistrationTypeEnum;
use App\Enums\RoleEnum;
use App\Models\Branch;
use App\Models\Registration;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class RegistrationReviewController extends Controller
{
    /**
     * Display a listing of the registration submission that need to be verified.
     */
    public function index(): View
    {
        $registrations = QueryBuilder::for(Registration::class)
            ->allowedFilters([
                AllowedFilter::partial('nama', 'user.nama'),
                AllowedFilter::partial('email', 'user.email'),
                AllowedFilter::exact('type'),
                AllowedFilter::exact('step'),
                AllowedFilter::exact('branch_id', 'user.branch_id'),
            ])
            ->where('status', RegistrationStatusEnum::DIPROSES)
            ->with('user.branch')
            ->latest('updated_at')
            ->paginate(15)
            ->appends(request()->query());

        $branches = Branch::select('id', 'name')
            ->orderBy('name', 'asc')
            ->pluck('name', 'id');

        return view('hris.registrasi.admin.index', compact('registrations', 'branches'));
    }

    /**
     * Display a listing of all registration submission.
     */
    public function indexLog(): View
    {
        $registrations = QueryBuilder::for(Registration::class)
            ->allowedFilters([
                AllowedFilter::partial('nama', 'user.nama'),
                AllowedFilter::partial('email', 'user.email'),
                AllowedFilter::exact('type'),
                AllowedFilter::exact('step'),
                AllowedFilter::exact('status'),
                AllowedFilter::exact('branch_id', 'user.branch_id'),
            ])
            ->with('user.branch')
            ->orderBy('updated_at', 'desc')
            ->paginate(15)
            ->appends(request()->query());

        $branches = Branch::select('id', 'name')
            ->orderBy('name', 'asc')
            ->pluck('name', 'id');

        return view('hris.registrasi.admin.index-log', compact('registrations', 'branches'));
    }

    /**
     * Display the specified registration submission details.
     */
    public function show(Registration $registration): View
    {
        if (! $registration->type) {
            abort(404);
        }

        /** @var \App\Models\User $user */
        $user = $registration->user;

        if (
            $registration->type
            == RegistrationTypeEnum::PENGURUS_WILAYAH
        ) {
            return view('hris.registrasi.admin.detail-pengurus', compact('registration', 'user'));
        }

        return view('hris.registrasi.admin.detail-relawan', compact('registration', 'user'));
    }

    /**
     * Handle the next step in the registration process.
     */
    public function nextStep(Request $request, Registration $registration): RedirectResponse
    {
        Gate::authorize('nextStep', $registration);

        $type = $registration->type;
        $currentStep = $registration->step;

        if ($type == RegistrationTypeEnum::RELAWAN_BARU) {
            if ($currentStep == RegistrationBaruStepEnum::WAWANCARA) {
                $validated = $request->validate([
                    'no_relawan' => [
                        'required',
                        'string',
                        'max:255',
                        'unique:temp_users',
                        Rule::unique('users')->ignore($registration->user),
                    ],
                ]);

                $registration->user->update([
                    'no_relawan' => $validated['no_relawan'],
                ]);
            } elseif ($currentStep == RegistrationBaruStepEnum::TERHUBUNG) {
                // Pada relawan baru, status approve menjadi 'TRUE' pada step terhubung
                // agar dapat segera mengakses dashboard
                $registration->user->update([
                    'is_approved' => 1,
                ]);
            }
        }

        $nextStep = $currentStep->cases()[$currentStep->index() + 1] ?? null;

        if ($nextStep) {
            $registration->update(['step' => $nextStep]);
        }

        flash()->success("Berhasil. Permohonan registrasi relawan atas nama [{$registration->user->nama}] telah berlanjut ke tahapan [{$nextStep?->value}].");

        return to_route('registrasi.show', $registration->id);
    }

    /**
     * Handle the request to revise a registration submission.
     */
    public function requestRevision(Request $request, Registration $registration): RedirectResponse
    {
        Gate::authorize('requestRevision', $registration);

        $validated = $request->validate([
            'message' => ['required', 'string', 'max:255'],
        ]);

        $registration->update([
            'status' => RegistrationStatusEnum::REVISI,
            'step' => ($registration->type == RegistrationTypeEnum::RELAWAN_BARU)
                ? RegistrationBaruStepEnum::MENGISI
                : RegistrationLamaStepEnum::MENGISI,
            'message' => $validated['message'],
        ]);

        // TODO: Kirim email

        flash()->success("Berhasil. Permintaan revisi telah dikirimkan kepada [{$registration->user->nama}].");

        return to_route('registrasi.show', $registration->id);
    }

    /**
     * Approve the registration process for a user.
     */
    public function approve(Request $request, Registration $registration): RedirectResponse
    {
        Gate::authorize('approve', $registration);

        $type = $registration->type;

        if ($type == RegistrationTypeEnum::RELAWAN_BARU) {
            $registration->user->syncRoles(RoleEnum::RELAWAN_WILAYAH);
        } elseif ($type == RegistrationTypeEnum::RELAWAN_WILAYAH) {
            $validated = $request->validate([
                'no_relawan' => [
                    'required',
                    'string',
                    'max:255',
                    'unique:temp_users',
                    Rule::unique('users')->ignore($registration->user),
                ],
            ]);

            $registration->user->update([
                'no_relawan' => $validated['no_relawan'],
                'is_approved' => 1,
            ]);
        } else {
            // For pengurus
            $registration->user->update([
                'is_approved' => 1,
            ]);
        }

        $registration->delete();

        // TODO: Kirim email

        flash()->success("Berhasil. Registrasi atas nama [{$registration->user->nama}] telah diselesaikan.");

        return to_route('registrasi.index');
    }

    /**
     * Reject a registration.
     */
    public function reject(Request $request, Registration $registration): RedirectResponse
    {
        Gate::authorize('reject', $registration);

        $validated = $request->validate([
            'message' => ['required', 'string', 'max:255'],
        ]);

        $registration->update([
            'status' => RegistrationStatusEnum::DITOLAK,
            'message' => $validated['message'],
        ]);

        // TODO: Kirim email

        flash()->success("Berhasil. Registrasi atas nama [{$registration->user->nama}] telah ditolak.");

        return to_route('registrasi.show', $registration->id);
    }

    /**
     * Remove the specified user and its registration.
     */
    public function destroy(Registration $registration): RedirectResponse
    {
        Gate::authorize('delete', $registration);

        $registration->user->delete();

        flash()->success("Berhasil. Registrasi atas nama [{$registration->user->nama}] telah dihapus.");

        $prevUrlQuery = parse_url(url()->previous(), PHP_URL_QUERY);
        if (url()->previous() == route('registrasi.indexLog', $prevUrlQuery)) {
            return to_route('registrasi.indexLog', $prevUrlQuery);
        }

        return to_route('registrasi.indexLog');
    }

    /**
     * Remove old registration records based on the provided criteria.
     */
    public function bulkDelete(Request $request): RedirectResponse
    {
        $total = 0;

        if ($request->input('step_mengisi')) {
            $registrations = Registration::where('step', 'mengisi')
                ->where('updated_at', '<', Carbon::now()->subDays($request->input('lama_mengisi')))
                ->get();
            $total += $registrations->count();

            foreach ($registrations as $registration) {
                $registration->user->delete();
            }
        }

        if ($request->input('status_ditolak')) {
            $registrations = Registration::where('status', RegistrationStatusEnum::DITOLAK)
                ->where('updated_at', '<', Carbon::now()->subDays($request->input('lama_ditolak')))
                ->get();
            $total += $registrations->count();

            foreach ($registrations as $registration) {
                $registration->user->delete();
            }
        }

        if ($total) {
            flash()->success("Berhasil. Sebanyak [{$total}] permohonan registrasi telah dihapus.");
        } else {
            flash()->info('Tidak ada data yang perlu dihapus.');
        }

        return to_route('registrasi.indexLog');
    }
}
