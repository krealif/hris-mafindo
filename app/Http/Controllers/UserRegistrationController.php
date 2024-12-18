<?php

namespace App\Http\Controllers;

use App\Enums\RegistrationBaruStepEnum;
use App\Enums\RegistrationLamaStepEnum;
use App\Enums\RegistrationStatusEnum;
use App\Enums\RegistrationTypeEnum;
use App\Enums\RoleEnum;
use App\Http\Requests\StoreRegistrationPengurusRequest;
use App\Http\Requests\StoreRegistrationRelawanRequest;
use App\Models\Branch;
use App\Models\Registration;
use App\Models\UserDetail;
use App\Traits\HandlesArrayInput;
use App\Traits\HasUploadFile;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class UserRegistrationController extends Controller
{
    use HandlesArrayInput, HasUploadFile;

    /**
     * Display a form selection page or redirect to the appropriate registration form
     * if the user already has a registration.
     */
    public function selectForm(): RedirectResponse|View
    {
        $registration = Auth::user()->registration;

        if ($registration) {
            return to_route('ajuan.showForm', $registration->type);
        }

        return view('hris.registrasi.type-selection');
    }

    /**
     * Display the registration form based on the registration type.
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
            'branches' => $branches,
        ];

        if ($type == RegistrationTypeEnum::PENGURUS_WILAYAH) {
            return view('hris.registrasi.form-pengurus', $viewData);
        }

        $viewData['detail'] = Auth::user()->detail;

        return view('hris.registrasi.form-relawan', $viewData);
    }

    /**
     * Store a new registration based on the given type.
     *
     * This method authorizes the creation of a new registration and delegates
     * the request handling to the appropriate method based on the registration type.
     *
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
     * Store a new or draft registration of relawan.
     *
     * This method validates the incoming request, processes the uploaded photo if present,
     * updates the user's registration data, assigns roles based on the registration type,
     * and saves the data.
     */
    public function storeRelawan(StoreRegistrationRelawanRequest $request, RegistrationTypeEnum $type): RedirectResponse
    {
        $validated = $request->validated();

        $validated = $this->handleArrayField($validated, [
            'pendidikan',
            'pekerjaan',
            'sertifikat',
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
            'status' => ($request->_mode == 'draft')
                ? RegistrationStatusEnum::DRAFT
                : RegistrationStatusEnum::DIPROSES,
            'step' => ($request->_mode == 'draft')
                ? ($type == RegistrationTypeEnum::RELAWAN_BARU
                    ? RegistrationBaruStepEnum::MENGISI
                    : RegistrationLamaStepEnum::MENGISI)
                : ($type == RegistrationTypeEnum::RELAWAN_BARU
                    ? RegistrationBaruStepEnum::PROFILING
                    : RegistrationLamaStepEnum::VERIFIKASI),
        ];

        if (
            $request->_mode == 'draft'
            && $registration?->status == 'revisi'
        ) {
            $registrationData['status'] = RegistrationStatusEnum::REVISI;
        }

        // Assign roles based on registration type
        if (! $user->hasAnyRole(['relawan', 'relawan-baru'])) {
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
                    'branch_id',
                ])
            );

            $detail = Arr::except($validated, [
                'nama',
                'no_relawan',
                'foto',
                'branch_id',
                'mode',
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

        if ($request->_mode == 'draft') {
            flash()->success('Berhasil. Data telah disimpan sementara.');
        } else {
            flash()->success('Berhasil. Pengajuan telah dikirimkan. Mohon tunggu tahapan selanjutnya dari admin.');
        }

        return to_route('ajuan.showForm', $type);
    }

    /**
     * Store a new or draft registration of pengurus.
     *
     * This method validates the request, updates the user's information, creates or updates the registration
     * and branch records in the database. The method also assigns the "pengurus" role to the user.
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
            'status' => ($request->_mode == 'draft')
                ? RegistrationStatusEnum::DRAFT
                : RegistrationStatusEnum::DIPROSES,
            'step' => ($request->_mode == 'draft')
                ? RegistrationLamaStepEnum::MENGISI
                : RegistrationLamaStepEnum::VERIFIKASI,
        ];

        if (
            $request->_mode == 'draft'
            && $registration?->status == 'revisi'
        ) {
            $registrationData['status'] = RegistrationStatusEnum::REVISI;
        }

        if (! $user->hasRole('pengurus')) {
            $user->assignRole(RoleEnum::PENGURUS);
        }

        DB::transaction(function () use ($user, $registrationData, $validated) {
            $user->update(
                Arr::only($validated, [
                    'nama',
                    'branch_id',
                ])
            );

            if ($user->branch_id) {
                Branch::updateOrCreate(
                    ['id' => $user->branch_id],
                    [
                        'pengurus' => $validated['pengurus'],
                        'id' => $user->branch_id,
                    ]
                );
            }

            Registration::updateOrCreate(
                ['user_id' => $user->id],
                [
                    ...$registrationData,
                    'user_id' => $user->id,
                ]
            );
        });

        if ($request->_mode == 'draft') {
            flash()->success('Berhasil. Data telah disimpan sementara.');
        } else {
            flash()->success('Berhasil. Pengajuan telah dikirimkan. Mohon tunggu tahapan selanjutnya dari admin.');
        }

        return to_route('ajuan.showForm', $type);
    }
}
