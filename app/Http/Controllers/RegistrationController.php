<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\UserDetail;
use Illuminate\Support\Arr;
use App\Models\Registration;
use App\Traits\HasUploadFile;
use Illuminate\Support\Facades\DB;
use App\Enums\RegistrationTypeEnum;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Enums\RegistrationStatusEnum;
use Illuminate\Http\RedirectResponse;
use App\Enums\RegistrationBaruStepEnum;
use App\Enums\RegistrationLamaStepEnum;
use App\Enums\RoleEnum;
use App\Http\Requests\StoreRegistrationRelawanRequest;
use App\Http\Requests\StoreRegistrationPengurusRequest;
use App\Traits\HandlesArrayInput;

class RegistrationController extends Controller
{
    use HasUploadFile, HandlesArrayInput;

    /**
     * Display a form selection page or redirect to the appropriate registration form
     * if the user already has a registration.
     */
    public function selectForm(): RedirectResponse | View
    {
        $registration = Auth::user()->registration;

        if ($registration) {
            return to_route('registration.showForm', $registration->type);
        }

        return view('hris.registrasi.type-selection');
    }

    /**
     * Show the form for submitting registration details
     * based on type.
     */
    public function showForm(RegistrationTypeEnum $type): View
    {
        Gate::authorize('viewForm', [Registration::class, $type]);

        $registration = Auth::user()->registration;

        $branches = Branch::select('id', 'nama')
            ->orderBy('nama', 'asc')
            ->pluck('nama', 'id');

        $viewData = [
            'type' => $type->value,
            'registration' => $registration,
            'branches' => $branches
        ];

        if ($type == RegistrationTypeEnum::PENGURUS_WILAYAH) {
            return view('hris.registrasi.form-wrapper', $viewData);
        }

        $viewData['detail'] = Auth::user()->detail;

        return view('hris.registrasi.form-wrapper', $viewData);
    }

    /**
     * Store the registration details based on the given type.
     */
    public function store(RegistrationTypeEnum $type): RedirectResponse
    {
        Gate::authorize('create', [Registration::class, $type]);

        if (str_starts_with($type->value, 'relawan-')) {
            return $this->storeRelawan(app(StoreRegistrationRelawanRequest::class), $type);
        } elseif (str_starts_with($type->value, 'pengurus-')) {
            return $this->storePengurus(app(StoreRegistrationPengurusRequest::class), $type);
        } else {
            abort(404);
        }
    }

    /**
     * Handle the storage of relawan registration.
     */
    public function storeRelawan(StoreRegistrationRelawanRequest $request, RegistrationTypeEnum $type): RedirectResponse
    {
        $validated = $request->validated();

        $validated = $this->handleArrayField($validated, [
            'pendidikan',
            'pekerjaan',
            'sertifikat'
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $registration = $user->registration;

        if ($request->hasFile('foto')) {
            $path = $this->uploadFile('profile', $validated['foto'], 'public');
            $validated['foto'] = $path;

            // remove old photo
            if ($user->foto) {
                $this->deleteFile($user->foto, 'public');
            }
        }

        $registrationData = [
            'type' => $type,
            'status' => ($request->mode == 'draft')
                ? RegistrationStatusEnum::DRAFT
                : RegistrationStatusEnum::DIPROSES,
            'step' => ($request->mode == 'draft')
                ? ($type == RegistrationTypeEnum::RELAWAN_BARU
                    ? RegistrationBaruStepEnum::MENGISI
                    : RegistrationLamaStepEnum::MENGISI)
                : ($type == RegistrationTypeEnum::RELAWAN_BARU
                    ? RegistrationBaruStepEnum::PROFILING
                    : RegistrationLamaStepEnum::VERIFIKASI),
        ];

        if (
            $request->mode == 'draft'
            && $registration?->status == 'revisi'
        ) {
            $registrationData['status'] = RegistrationStatusEnum::REVISI;
        }

        // Assign roles based on registration type
        if (!$user->hasAnyRole(['relawan', 'relawan-baru'])) {
            $role = ($type == RegistrationTypeEnum::RELAWAN_BARU)
                ? RoleEnum::RELAWAN_BARU
                : RoleEnum::RELAWAN;

            $user->assignRole($role);
        }

        DB::transaction(function () use ($user, $registrationData, $validated) {
            $user->update(
                Arr::only($validated, [
                    'nama',
                    'no_relawan',
                    'foto',
                    'branch_id'
                ])
            );

            $detail = Arr::except($validated, [
                'nama',
                'no_relawan',
                'foto',
                'branch_id',
                'mode'
            ]);

            UserDetail::updateOrCreate(
                ['user_id' => $user->id],
                [
                    ...$detail,
                    'user_id' => $user->id,
                ]
            );

            Registration::updateOrCreate(
                ['user_id' => $user->id],
                [
                    ...$registrationData,
                    'user_id' => $user->id,
                ]
            );
        });

        flash()->success("Berhasil!");
        return to_route('registration.showForm', $type);
    }

    /**
     * Handle the storage of pengurus registration.
     */
    public function storePengurus(StoreRegistrationPengurusRequest $request, RegistrationTypeEnum $type): RedirectResponse
    {
        Gate::authorize('create', [Registration::class, $type]);

        $validated = $request->validated();

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $registration = $user->registration;

        $registrationData = [
            'type' => $type,
            'status' => ($request->mode == 'draft')
                ? RegistrationStatusEnum::DRAFT
                : RegistrationStatusEnum::DIPROSES,
            'step' => ($request->mode == 'draft')
                ? RegistrationLamaStepEnum::MENGISI
                : RegistrationLamaStepEnum::VERIFIKASI,
        ];

        if (
            $request->mode == 'draft'
            && $registration?->status == 'revisi'
        ) {
            $registrationData['status'] = RegistrationStatusEnum::REVISI;
        }

        if (!$user->hasRole('pengurus')) {
            $user->assignRole(RoleEnum::PENGURUS);
        }

        DB::transaction(function () use ($user, $registrationData, $validated) {
            $user->update(
                Arr::only($validated, [
                    'nama',
                    'branch_id'
                ])
            );

            Branch::updateOrCreate(
                ['id' => $user->branch_id],
                [
                    'pengurus' => $validated['pengurus'],
                    'id' => $user->branch_id,
                ]
            );

            Registration::updateOrCreate(
                ['user_id' => $user->id],
                [
                    ...$registrationData,
                    'user_id' => $user->id,
                ]
            );
        });

        flash()->success("Berhasil!");
        return to_route('registration.showForm', $type);
    }
}
