<?php

namespace App\Http\Controllers;

use App\Enums\RegistrationStatusEnum;
use App\Enums\RegistrationBaruStepEnum;
use App\Models\Branch;
use Illuminate\Support\Arr;
use App\Models\Registration;
use App\Traits\HasUploadFile;
use Illuminate\Support\Facades\DB;
use App\Enums\RegistrationTypeEnum;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\StoreRegistrationRequest;
use App\Models\UserDetail;

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
        $detail = Auth::user()->detail;
        $branches = Branch::all()->pluck('nama', 'id');

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
    public function store(StoreRegistrationRequest $request, string $type)
    {
        Gate::authorize('create', [Registration::class, $type]);

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

        if ($request->mode == 'submit') {
            $registration = [
                'type' => $type,
                'status' => RegistrationStatusEnum::DIPROSES->value,
                'step' => RegistrationBaruStepEnum::PROFILING->value,
                'updated_at' => \Carbon\Carbon::now()
            ];
        } else {
            $registration = [
                'type' => $type,
                'status' => RegistrationStatusEnum::DRAFT->value,
                'step' => RegistrationBaruStepEnum::MENGISI->value,
            ];

            if ($user->registration?->status == 'revisi') {
                $registration['status'] = RegistrationStatusEnum::REVISI->value;
            }
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
}
