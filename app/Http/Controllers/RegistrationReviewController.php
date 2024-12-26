<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Branch;
use App\Enums\RoleEnum;
use Illuminate\View\View;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Enums\RegistrationTypeEnum;
use Illuminate\Support\Facades\Gate;
use App\Enums\RegistrationStatusEnum;
use Illuminate\Http\RedirectResponse;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Enums\RegistrationBaruStepEnum;
use App\Enums\RegistrationLamaStepEnum;

class RegistrationReviewController extends Controller
{
    /**
     * Display a listing of the registration submission that need to be verified.
     */
    public function index(): View
    {
        $registrations = QueryBuilder::for(Registration::class)
            ->allowedFilters([
                'user.nama',
                'user.email',
                AllowedFilter::exact('type'),
                AllowedFilter::exact('step'),
                AllowedFilter::exact('user.branch_id'),
            ])
            ->where('status', 'diproses')
            ->with('user.branch')
            ->latest('updated_at')
            ->paginate(15)
            ->appends(request()->query());

        $branches = Branch::select('id', 'nama')
            ->orderBy('nama', 'asc')
            ->pluck('nama', 'id');

        return view('hris.registrasi.admin.index', compact('registrations', 'branches'));
    }

    /**
     * Display a listing of the registration submission history.
     */
    public function indexHistory(): View
    {
        $registrations = QueryBuilder::for(Registration::class)
            ->allowedFilters([
                'user.nama',
                'user.email',
                AllowedFilter::exact('type'),
                AllowedFilter::exact('step'),
                AllowedFilter::exact('status'),
                AllowedFilter::exact('user.branch_id'),
            ])
            ->with('user.branch')
            ->orderBy('updated_at', 'desc')
            ->paginate(15)
            ->appends(request()->query());

        $branches = Branch::select('id', 'nama')
            ->orderBy('nama', 'asc')
            ->pluck('nama', 'id');

        return view('hris.registrasi.admin.histori', compact('registrations', 'branches'));
    }

    /**
     * Display the specified registration submission details.
     */
    public function show(Registration $registration): View
    {
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
        $stepEnum = $type == RegistrationTypeEnum::RELAWAN_BARU
            ? RegistrationBaruStepEnum::class
            : RegistrationLamaStepEnum::class;

        $currentStep = $stepEnum::from($registration->step);

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
                $registration->user->update([
                    'is_verified' => 1,
                ]);
            }
        }

        $nextStep = $stepEnum::cases()[$currentStep->index() + 1] ?? null;

        if ($nextStep) {
            $registration->update(['step' => $nextStep]);
        }

        flash()->success("Berhasil. Proses registrasi relawan atas nama [{$registration->user->nama}] telah beralih ke tahapan [{$nextStep?->value}].");

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
     * Complete the registration process for a user.
     */
    public function finishRegistration(Request $request, Registration $registration): RedirectResponse
    {
        Gate::authorize('finish', $registration);

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
                'is_verified' => 1,
            ]);
        } else {
            // For pengurus
            $registration->user->update([
                'is_verified' => 1,
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
    public function rejectRegistration(Request $request, Registration $registration): RedirectResponse
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
        Gate::authorize('destroy', $registration);

        $registration->user->delete();

        flash()->success("Berhasil. Registrasi atas nama [{$registration->user->nama}] telah dihapus.");

        $prevUrlQuery = parse_url(url()->previous(), PHP_URL_QUERY);
        if (url()->previous() == route('registrasi.history', $prevUrlQuery)) {
            return to_route('registrasi.history', $prevUrlQuery);
        }

        return to_route('registrasi.history');
    }

    /**
     * Remove old registration records based on the provided criteria.
     */
    public function prune(Request $request): RedirectResponse
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
            $registrations = Registration::where('status', 'ditolak')
                ->where('updated_at', '<', Carbon::now()->subDays($request->input('lama_ditolak')))
                ->get();
            $total += $registrations->count();

            foreach ($registrations as $registration) {
                $registration->user->delete();
            }
        }

        if ($total) {
            flash()->success("Berhasil. Sebanyak [{$total}] data telah dihapus.");
        } else {
            flash()->info('Tidak ada data yang perlu dihapus.');
        }

        return to_route('registrasi.history');
    }
}
