<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\UserDetail;
use Illuminate\Support\Arr;
use App\Models\Registration;
use Illuminate\Http\Request;
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
use App\Http\Requests\StoreRegistrationRelawanRequest;
use App\Http\Requests\StoreRegistrationPengurusRequest;

class RegistrationController extends Controller
{
    use HasUploadFile;
    /**
     * Display a listing of the resource.
     */
    public function selectForm(): RedirectResponse | View
    {
        $registration = Auth::user()->registration;
        if ($registration) {
            return to_route('registration.showForm', $registration->type);
        }

        return view('hris.registrasi.form-selection');
    }

    /**
     * Show the form for submitting registration details.
     */
    public function showForm(string $type): View
    {
        Gate::authorize('viewForm', [Registration::class, $type]);
        if (!RegistrationTypeEnum::tryFrom($type)) {
            abort(404);
        }

        $registration = Auth::user()->registration;
        $branches = Branch::all(['id', 'nama'])->pluck('nama', 'id');

        if ($type == RegistrationTypeEnum::PENGURUS_WILAYAH->value) {
            return view('hris.registrasi.form-wrapper', compact(
                'type',
                'registration',
                'branches'
            ));
        }

        $detail = Auth::user()->detail;

        return view('hris.registrasi.form-wrapper', compact(
            'type',
            'registration',
            'detail',
            'branches'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(string $type)
    {
        Gate::authorize('create', [Registration::class, $type]);

        if (!RegistrationTypeEnum::tryFrom($type)) {
            abort(404);
        }

        if (str_starts_with($type, 'relawan-')) {
            return $this->storeRelawan(app(StoreRegistrationRelawanRequest::class), $type);
        } elseif (str_starts_with($type, 'pengurus-')) {
            return $this->storePengurus(app(StoreRegistrationPengurusRequest::class), $type);
        } else {
            abort(404);
        }
    }

    public function storeRelawan(StoreRegistrationRelawanRequest $request, string $type)
    {
        $validated = $request->validated();

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($request->hasFile('foto')) {
            $path = $this->uploadFile('profile', $validated['foto'], 'public');
            $validated['foto'] = $path;

            // remove old photo
            if ($user->foto) {
                $this->deleteFile($user->foto, 'public');
            }
        }

        if ($request->mode == 'draft') {
            $registration = [
                'type' => $type,
                'status' => RegistrationStatusEnum::DRAFT->value,
                'step' => ($type == RegistrationTypeEnum::RELAWAN_BARU->value)
                    ? RegistrationBaruStepEnum::MENGISI->value
                    : RegistrationLamaStepEnum::MENGISI->value,
            ];

            if ($user->registration?->status == 'revisi') {
                $registration['status'] = RegistrationStatusEnum::REVISI->value;
            }
        } else {
            $registration = [
                'type' => $type,
                'status' => RegistrationStatusEnum::DIPROSES->value,
                'step' => ($type == RegistrationTypeEnum::RELAWAN_BARU->value)
                    ? RegistrationBaruStepEnum::PROFILING->value
                    : RegistrationLamaStepEnum::VERIFIKASI->value,
            ];
        }

        DB::transaction(function () use ($user, $registration, $validated) {
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
                    ...$registration,
                    'user_id' => $user->id,
                ]
            );
        });

        flash()->success("Berhasil!");
        return to_route('registration.showForm', $type);
    }

    public function storePengurus(StoreRegistrationPengurusRequest $request, string $type)
    {
        Gate::authorize('create', [Registration::class, $type]);

        $validated = $request->validated();

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($request->mode == 'draft') {
            $registration = [
                'type' => $type,
                'status' => RegistrationStatusEnum::DRAFT->value,
                'step' => RegistrationLamaStepEnum::MENGISI->value,
            ];

            if ($user->registration?->status == 'revisi') {
                $registration['status'] = RegistrationStatusEnum::REVISI->value;
            }
        } else {
            $registration = [
                'type' => $type,
                'status' => RegistrationStatusEnum::DIPROSES->value,
                'step' => RegistrationLamaStepEnum::VERIFIKASI->value,
            ];
        }

        DB::transaction(function () use ($user, $registration, $validated) {
            $user->update(
                Arr::only($validated, [
                    'nama',
                    'branch_id'
                ])
            );

            $pengurus = array_merge(['ketua' => $user->nama], $validated['pengurus']);

            Branch::updateOrCreate(
                ['id' => $user->branch_id],
                [
                    'pengurus' => $pengurus,
                    'id' => $user->branch_id,
                ]
            );

            Registration::updateOrCreate(
                ['user_id' => $user->id],
                [
                    ...$registration,
                    'user_id' => $user->id,
                ]
            );
        });

        flash()->success("Berhasil!");
        return to_route('registration.showForm', $type);
    }
}
