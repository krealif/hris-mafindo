<?php

namespace App\Http\Controllers;

use App\Enums\RoleEnum;
use Illuminate\View\View;
use App\Models\Registration;
use Illuminate\Http\Request;
use App\Enums\RegistrationTypeEnum;
use Illuminate\Support\Facades\Gate;
use App\Enums\RegistrationStatusEnum;
use Illuminate\Http\RedirectResponse;
use App\Enums\RegistrationBaruStepEnum;
use App\Enums\RegistrationLamaStepEnum;

class RegistrationVerifController extends Controller
{
    /**
     * Display a listing of the registration.
     */
    public function indexRelawan(): View
    {
        $registrations = Registration::whereIn('type', ['relawan-baru', 'relawan-wilayah'])
            ->whereNotIn('status', ['draft', 'selesai'])
            ->with('user.branch')
            ->orderBy('updated_at', 'asc')
            ->get();

        return view('hris.verifikasi.list-relawan', compact('registrations'));
    }

    /**
     * Display the specified registration of relawan.
     */
    public function showRelawan(int $registration): View
    {
        $registration = Registration::where('id', $registration)
            ->whereIn('type', ['relawan-baru', 'relawan-wilayah'])
            ->firstOrFail();

        $user = $registration->user;

        return view('hris.verifikasi.detail-relawan', compact('registration', 'user'));
    }

    /**
     * Proceed to the next step of the registration process.
     */
    public function nextStep(Request $request, Registration $registration): RedirectResponse
    {
        Gate::authorize('updateStep', $registration);

        $type = $registration->type;
        $stepEnum = $type === RegistrationTypeEnum::RELAWAN_BARU->value
            ? RegistrationBaruStepEnum::class
            : RegistrationLamaStepEnum::class;

        $currentStep = $stepEnum::from($registration->step);

        if ($type === RegistrationTypeEnum::RELAWAN_BARU->value) {
            if ($currentStep === RegistrationBaruStepEnum::WAWANCARA) {
                $validated = $request->validate([
                    'no_relawan' => ['required', 'string'],
                ]);

                $registration->user->update(['no_relawan' => $validated['no_relawan']]);
            } elseif ($currentStep === RegistrationBaruStepEnum::TERHUBUNG) {
                $registration->user->update([
                    'is_verified' => 1,
                ]);
            }
        }

        $nextStep = $stepEnum::cases()[$currentStep->index() + 1] ?? null;

        if ($nextStep) {
            $registration->update(['step' => $nextStep->value]);
        }

        return to_route('verif.detailRelawan', $registration->id);
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

        return to_route('verif.detailRelawan', $registration->id);
    }


    /**
     * Mark the registration process as completed.
     */
    public function finishRegistration(Request $request, Registration $registration): RedirectResponse
    {
        Gate::authorize('finishStep', $registration);

        $type = $registration->type;
        if ($type === RegistrationTypeEnum::RELAWAN_BARU->value) {
            $registration->user->syncRoles(RoleEnum::RELAWAN);
        } elseif ($type === RegistrationTypeEnum::RELAWAN_WILAYAH->value) {
            $validated = $request->validate([
                'no_relawan' => ['required', 'string'],
            ]);

            $registration->user->update([
                'no_relawan' => $validated['no_relawan'],
                'is_verified' => 1,
            ]);
        }

        $registration->update([
            'status' => RegistrationStatusEnum::SELESAI,
            'step' => 'terdaftar',
            'message' => null
        ]);

        flash()->success("Berhasil!");
        return to_route('verif.indexRelawan');
    }
}
