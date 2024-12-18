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
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class RegistrationSubmissionController extends Controller
{
    /**
     * Display a listing of the all registration.
     */
    public function index(): View
    {
        $registrations = QueryBuilder::for(Registration::class)
            ->allowedFilters([
                'user.nama',
                'user.email',
                AllowedFilter::exact('type'),
                AllowedFilter::exact('status'),
                AllowedFilter::exact('user.branch_id'),
            ])
            ->where('status', 'diproses')
            ->with('user.branch')
            ->orderBy('updated_at', 'desc')
            ->paginate(20)
            ->appends(request()->query());

        $branches = Branch::select('id', 'nama')
            ->orderBy('nama', 'asc')
            ->pluck('nama', 'id');

        return view('hris.verifikasi.index', compact('registrations', 'branches'));
    }

    /**
     * Display a listing of the all registration.
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
            ->paginate(20)
            ->appends(request()->query());

        $branches = Branch::select('id', 'nama')
            ->orderBy('nama', 'asc')
            ->pluck('nama', 'id');

        return view('hris.verifikasi.histori', compact('registrations', 'branches'));
    }

    /**
     * Display the specified registration of relawan or pengurus.
     */
    public function show(Registration $registration): View
    {
        $user = $registration->user;

        if (
            $registration->type
            == RegistrationTypeEnum::PENGURUS_WILAYAH->value
        ) {
            return view('hris.verifikasi.detail-pengurus', compact('registration', 'user'));
        }

        return view('hris.verifikasi.detail-relawan', compact('registration', 'user'));
    }

    /**
     * Proceed to the next step of the registration process.
     */
    public function nextStep(Request $request, Registration $registration): RedirectResponse
    {
        Gate::authorize('nextStep', $registration);

        $type = $registration->type;
        $stepEnum = $type == RegistrationTypeEnum::RELAWAN_BARU->value
            ? RegistrationBaruStepEnum::class
            : RegistrationLamaStepEnum::class;

        $currentStep = $stepEnum::from($registration->step);

        if ($type == RegistrationTypeEnum::RELAWAN_BARU->value) {
            if ($currentStep == RegistrationBaruStepEnum::WAWANCARA) {
                $validated = $request->validate([
                    'no_relawan' => ['required', 'string'],
                ]);

                $registration->user->update(['no_relawan' => $validated['no_relawan']]);
            } elseif ($currentStep == RegistrationBaruStepEnum::TERHUBUNG) {
                $registration->user->update([
                    'is_verified' => 1,
                ]);
            }
        }

        $nextStep = $stepEnum::cases()[$currentStep->index() + 1] ?? null;

        if ($nextStep) {
            $registration->update(['step' => $nextStep->value]);
        }

        flash()->success("Berhasil. Proses registrasi relawan atas nama [{$registration->user->nama}] telah beralih ke tahapan [{$nextStep?->value}].");

        return back();
    }

    /**
     * Handle actions specific to the current registration step.
     */
    public function requestRevision(Request $request, Registration $registration): RedirectResponse
    {
        Gate::authorize('requestRevision', $registration);

        $validated = $request->validate([
            'message' => ['required', 'string', 'max:255'],
        ]);

        $registration->update([
            'status' => RegistrationStatusEnum::REVISI,
            'step' => ($registration->type == RegistrationTypeEnum::RELAWAN_BARU->value)
                ? RegistrationBaruStepEnum::MENGISI
                : RegistrationLamaStepEnum::MENGISI,
            'message' => $validated['message'],
        ]);

        // TODO: Kirim email

        flash()->success("Berhasil. Permintaan revisi telah dikirimkan kepada [{$registration->user->nama}].");

        return back();
    }

    /**
     * Mark the registration process as completed.
     */
    public function finishRegistration(Request $request, Registration $registration): RedirectResponse
    {
        Gate::authorize('finish', $registration);

        $type = $registration->type;

        if ($type == RegistrationTypeEnum::RELAWAN_BARU->value) {
            $registration->user->syncRoles(RoleEnum::RELAWAN);
        } elseif ($type == RegistrationTypeEnum::RELAWAN_WILAYAH->value) {
            $validated = $request->validate([
                'no_relawan' => ['required', 'string'],
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

        return to_route('ajuan.index');
    }

    /**
     * Mark the registration process as completed.
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

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Registration $registration): RedirectResponse
    {
        Gate::authorize('destroy', $registration);

        /** @var \App\Models\User $user */
        $user = $registration->user;

        if ($user->foto) {
            Storage::disk('public')->delete($user->foto);
        }

        // Remove role
        $user->syncRoles([]);
        $user->delete();

        flash()->success("Berhasil. Registrasi atas nama [{$registration->user->nama}] telah dihapus.");

        if (url()->previous() != route('ajuan.history')) {
            return to_route('ajuan.history');
        }

        return back();
    }

    public function prune(Request $request): RedirectResponse
    {
        $total = 0;

        if ($request->input('step_mengisi')) {
            $result = Registration::where('step', 'mengisi')
                ->where('updated_at', '<', Carbon::now()->subDays($request->input('lama_mengisi')))
                ->delete();
            $total += $result;
        }

        if ($request->input('status_ditolak')) {
            $result = Registration::where('status', 'ditolak')
                ->where('updated_at', '<', Carbon::now()->subDays($request->input('lama_ditolak')))
                ->delete();
            $total += $result;
        }

        if ($total) {
            flash()->success("Berhasil. Sebanyak [{$total}] data telah dihapus.");
        } else {
            flash()->info('Tidak ada data yang perlu dihapus.');
        }

        return back();
    }
}
