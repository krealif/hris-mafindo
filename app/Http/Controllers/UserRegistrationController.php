<?php

namespace App\Http\Controllers;

use App\Enums\RegistrationBaruStepEnum;
use App\Enums\RegistrationLamaStepEnum;
use App\Enums\RegistrationStatusEnum;
use App\Enums\RegistrationTypeEnum;
use App\Http\Requests\StoreRegistrationPengurusRequest;
use App\Http\Requests\StoreRegistrationRelawanRequest;
use App\Models\Branch;
use App\Models\Registration;
use App\Models\UserDetail;
use App\Traits\FilterArrayInput;
use App\Traits\HasUploadFile;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class UserRegistrationController extends Controller
{
    use FilterArrayInput;
    use HasUploadFile;

    /**
     * Display a form selection page or redirect to the appropriate registration form
     * if the user already has a registration.
     */
    public function selectForm(): RedirectResponse|View
    {
        $registration = Auth::user()->registration;

        if ($registration?->type) {
            return to_route('registrasi.showForm', $registration->type);
        }

        return view('hris.registrasi.user.type-selection');
    }

    /**
     * Display the registration form based on the registration type.
     */
    public function showForm(RegistrationTypeEnum $type): View
    {
        Gate::authorize('viewForm', [Registration::class, $type]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $registration = $user->registration;

        $branches = Branch::select('id', 'name')
            ->orderBy('name', 'asc')
            ->pluck('name', 'id');

        $viewData = [
            'user' => $user,
            'registration' => $registration,
            'type' => $type,
            'branches' => $branches,
        ];

        if ($type == RegistrationTypeEnum::PENGURUS_WILAYAH) {
            return view('hris.registrasi.user.form-pengurus', $viewData);
        }

        $viewData['userDetail'] = $user->detail;

        return view('hris.registrasi.user.form-relawan', $viewData);
    }

    /**
     * Store a new registration based on the given type.
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
     */
    public function storeRelawan(StoreRegistrationRelawanRequest $request, RegistrationTypeEnum $type): RedirectResponse
    {
        $validated = $request->validated();

        // Filter untuk menghapus entri kosong dalam array
        $validated = $this->filterArrayInput($validated, [
            'medsos',
            'pendidikan',
            'pekerjaan',
            'sertifikat',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $registration = $user->registration;

        // Unggah foto profil pengguna
        if ($request->hasFile('foto')) {
            $path = $this->uploadFile('profile', $validated['foto'], 'public');
            $validated['foto'] = $path;
        }

        $registrationData = [
            'type' => $type,
            // Jika disimpan dalam mode 'DRAFT', atur status ke 'DRAFT', jika tidak, atur ke 'DIPROSES'
            'status' => ($request->boolean('_isDraft'))
                ? RegistrationStatusEnum::DRAFT
                : RegistrationStatusEnum::DIPROSES,
            'step' => ($request->boolean('_isDraft'))
                // Jika disimpan dalam mode 'DRAFT', atur step ke 'MENGISI' untuk relawan baru atau lama
                ? ($type == RegistrationTypeEnum::RELAWAN_BARU
                    ? RegistrationBaruStepEnum::MENGISI
                    : RegistrationLamaStepEnum::MENGISI)
                : ($type == RegistrationTypeEnum::RELAWAN_BARU
                    // Jika tidak, atur step berdasarkan jenis registrasi
                    ? RegistrationBaruStepEnum::PROFILING
                    : RegistrationLamaStepEnum::VERIFIKASI),
        ];

        // Bila status registrasi dalam 'REVISI' dan disimpan sebagai 'DRAFT', pertahankan status sebagai 'REVISI'
        if (
            $registration?->status == RegistrationStatusEnum::REVISI
            && $request->boolean('_isDraft')
        ) {
            $registrationData['status'] = RegistrationStatusEnum::REVISI;
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

        if ($request->boolean('_isDraft')) {
            flash()->success('Berhasil. Data telah disimpan sementara.');
        } else {
            flash()->success('Berhasil. Permohonan registrasi telah dikirim. Mohon tunggu tahapan selanjutnya dari admin.');
        }

        return to_route('registrasi.showForm', $type);
    }

    /**
     * Store a new or draft registration of pengurus.
     */
    public function storePengurus(StoreRegistrationPengurusRequest $request, RegistrationTypeEnum $type): RedirectResponse
    {
        Gate::authorize('create', [Registration::class, $type]);

        $validated = $request->validated();

        // Filter untuk menghapus entri kosong dalam array
        $validated = $this->filterArrayInput($validated, ['staff']);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $registration = $user->registration;

        $registrationData = [
            'type' => $type,
            // Jika disimpan dalam mode 'DRAFT', atur status ke 'DRAFT', jika tidak, atur ke 'DIPROSES'
            'status' => ($request->boolean('_isDraft'))
                ? RegistrationStatusEnum::DRAFT
                : RegistrationStatusEnum::DIPROSES,
            // Jika disimpan dalam mode 'DRAFT', atur status ke 'MENGISI', jika tidak, atur ke 'VERIFIKASI'
            'step' => ($request->boolean('_isDraft'))
                ? RegistrationLamaStepEnum::MENGISI
                : RegistrationLamaStepEnum::VERIFIKASI,
        ];

        // Bila status registrasi dalam 'REVISI' dan disimpan sebagai 'DRAFT', pertahankan status sebagai 'REVISI'
        if (
            $registration?->status == RegistrationStatusEnum::REVISI
            && $request->boolean('_isDraft')
        ) {
            $registrationData['status'] = RegistrationStatusEnum::REVISI;
        }

        DB::transaction(function () use ($user, $registrationData, $validated) {
            $user->update([
                'nama' => $validated['coordinatorName'],
                'branch_id' => $validated['branch_id']
            ]);

            if ($user->branch_id) {
                Branch::updateOrCreate(
                    ['id' => $user->branch_id],
                    [
                        'staff' => $validated['staff'],
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

        if ($request->boolean('_isDraft')) {
            flash()->success('Berhasil. Data telah disimpan sementara.');
        } else {
            flash()->success('Berhasil. Permohonan registrasi telah dikirim. Mohon tunggu tahapan selanjutnya dari admin.');
        }

        return to_route('registrasi.showForm', $type);
    }
}
