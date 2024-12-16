<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Enums\RoleEnum;
use Illuminate\View\View;
use App\Models\Registration;
use Illuminate\Http\Request;
use App\Enums\RegistrationTypeEnum;
use Illuminate\Support\Facades\Gate;
use App\Enums\RegistrationStatusEnum;
use Illuminate\Http\RedirectResponse;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Enums\RegistrationBaruStepEnum;
use App\Enums\RegistrationLamaStepEnum;
use Illuminate\Support\Facades\Storage;

class RegistrationVerifController extends Controller
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
                AllowedFilter::exact('user.branch_id')
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
                AllowedFilter::exact('user.branch_id')
            ])
            ->whereNot('status', 'selesai')
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
        ) return view('hris.verifikasi.detail-relawan', compact('registration', 'user'));

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

        flash()->success("Berhasil! Proses registrasi relawan atas nama [{$registration->user->nama}] telah beralih ke tahapan [{$nextStep?->value}].");

        return to_route('verif.show', $registration->id);
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

        flash()->success("Berhasil! Permintaan revisi telah dikirimkan kepada [{$registration->user->nama}].");

        return to_route('verif.show', $registration->id);
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

        $registration->update([
            'status' => RegistrationStatusEnum::SELESAI,
            'step' => 'terdaftar',
            'message' => null
        ]);

        // TODO: Kirim email

        flash()->success("Berhasil! Registrasi atas nama [{$registration->user->nama}] telah diselesaikan.");
        return to_route('verif.index');
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

        flash()->success("Berhasil! Registrasi atas nama [{$registration->user->nama}] telah ditolak.");

        return to_route('verif.show', $registration->id);
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

        flash()->success("Berhasil! Registrasi atas nama [{$registration->user->nama}] telah dihapus.");
        return back();
    }
}
