<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Branch;
use App\Enums\RoleEnum;
use Illuminate\View\View;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Enums\RegistrationTypeEnum;
use Illuminate\Support\Facades\Gate;
use App\Enums\RegistrationStatusEnum;
use Illuminate\Http\RedirectResponse;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Enums\RegistrationBaruStepEnum;
use App\Enums\RegistrationLamaStepEnum;
use App\Notifications\RegistrationApproved;
use App\Notifications\RegistrationRejected;
use App\Notifications\RegistrationRevision;

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

        /** @var \App\Models\User $registrationUser */
        $registrationUser = $registration->user;

        if ($type == RegistrationTypeEnum::RELAWAN_BARU) {
            if ($currentStep == RegistrationBaruStepEnum::WAWANCARA) {
                $validated = $request->validate([
                    'no_relawan' => [
                        'required',
                        'string',
                        'max:255',
                        'unique:temp_users',
                        Rule::unique('users')->ignore($registrationUser),
                    ],
                ]);

                $registrationUser->update([
                    'no_relawan' => $validated['no_relawan'],
                ]);
            } elseif ($currentStep == RegistrationBaruStepEnum::TERHUBUNG) {
                // Menetapkan role dan status approve agar dapat mengakses dashboard
                DB::transaction(function () use ($registrationUser) {
                    $registrationUser->assignRole(RoleEnum::RELAWAN_BARU);
                    $registrationUser->update([
                        'is_approved' => 1,
                    ]);
                });

                $registrationUser->notify(new RegistrationApproved(
                    $registrationUser,
                    $registration->type?->label() ?? ''
                ));
            }
        }

        $nextStep = $currentStep->cases()[$currentStep->index() + 1] ?? null;

        if ($nextStep) {
            $registration->update(['step' => $nextStep]);
        }

        flash()->success("Berhasil. Permohonan registrasi relawan atas nama [{$registration->user->nama}] telah berlanjut ke tahap [{$nextStep?->value}].");

        return to_route('registrasi.show', $registration->id);
    }

    /**
     * Handle the request to revise a registration submission.
     */
    public function requestRevision(Request $request, Registration $registration): RedirectResponse
    {
        Gate::authorize('requestRevision', $registration);

        $validated = $request->validate([
            'message' => ['required', 'string', 'max:750'],
        ]);

        $registration->update([
            'status' => RegistrationStatusEnum::REVISI,
            'step' => ($registration->type == RegistrationTypeEnum::RELAWAN_BARU)
                ? RegistrationBaruStepEnum::MENGISI
                : RegistrationLamaStepEnum::MENGISI,
            'message' => $validated['message'],
        ]);

        /** @var \App\Models\User $registrationUser */
        $registrationUser = $registration->user;
        $registrationUser->notify(new RegistrationRevision($registrationUser,  $validated['message']));

        flash()->success("Berhasil. Permintaan revisi telah dikirimkan kepada [{$registrationUser->nama}].");

        return to_route('registrasi.show', $registration->id);
    }

    /**
     * Approve the registration process for a user.
     */
    public function approve(Request $request, Registration $registration): RedirectResponse
    {
        Gate::authorize('approve', $registration);

        if ($registration->type == RegistrationTypeEnum::RELAWAN_WILAYAH) {
            $request->validate([
                'no_relawan' => [
                    'required',
                    'string',
                    'max:255',
                    'unique:temp_users',
                    Rule::unique('users')->ignore($registration->user),
                ],
            ]);
        }

        DB::transaction(function () use ($request, $registration) {
            $type = $registration->type;
            $registrationUser = $registration->user;

            if ($type == RegistrationTypeEnum::RELAWAN_BARU) {
                // Pada tahap ini, relawan baru telah mengikuti PDR
                // Maka dari itu, role diubah menjadi Relawan Wilayah
                $registration->user->syncRoles(RoleEnum::RELAWAN_WILAYAH);
            } elseif ($type == RegistrationTypeEnum::RELAWAN_WILAYAH) {
                // Pada tahap ini, admin dapat menambahkan/mengedit no relawan
                $registrationUser->syncRoles(RoleEnum::RELAWAN_WILAYAH);
                $registrationUser->update([
                    'no_relawan' => $request->input('no_relawan'),
                    'is_approved' => 1,
                ]);
            } elseif ($type == RegistrationTypeEnum::PENGURUS_WILAYAH) {
                $registrationUser->syncRoles(RoleEnum::PENGURUS_WILAYAH);
                $registrationUser->update([
                    'is_approved' => 1,
                ]);
            }

            $registration->delete();
        });

        /** @var \App\Models\User $registrationUser */
        $registrationUser = $registration->user;

        if (in_array($registration->type, [
            RegistrationTypeEnum::RELAWAN_WILAYAH,
            RegistrationTypeEnum::PENGURUS_WILAYAH
        ])) {
            $registrationUser->notify(new RegistrationApproved(
                $registrationUser,
                $registration->type->label()
            ));
        }

        flash()->success("Berhasil. Registrasi atas nama [{$registrationUser->nama}] telah diselesaikan.");

        return to_route('registrasi.index');
    }

    /**
     * Reject a registration.
     */
    public function reject(Request $request, Registration $registration): RedirectResponse
    {
        Gate::authorize('reject', $registration);

        $validated = $request->validate([
            'message' => ['required', 'string', 'max:750'],
        ]);

        $registration->update([
            'status' => RegistrationStatusEnum::DITOLAK,
            'message' => $validated['message'],
        ]);

        /** @var \App\Models\User $registrationUser */
        $registrationUser = $registration->user;

        $registrationUser->notify(new RegistrationRejected(
            $registrationUser,
            $validated['message'],
            $registration->type?->label() ?? ''
        ));

        flash()->success("Berhasil. Registrasi atas nama [{$registrationUser->nama}] telah ditolak.");

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
     * Remove old registration records based on the provided conditions.
     */
    public function bulkDelete(Request $request): RedirectResponse
    {
        $conditions = [
            'step_mengisi' => [
                'field' => 'step',
                'value' => 'mengisi',
                'duration' => $request->input('lama_mengisi')
            ],
            'status_ditolak' => [
                'field' => 'status',
                'value' => RegistrationStatusEnum::DITOLAK,
                'duration' => $request->input('lama_ditolak')
            ]
        ];

        $total = 0;

        foreach ($conditions as $key => $condition) {
            if ($request->input($key)) {
                $duration = $condition['duration'];

                // Lazy load users and fire events
                User::whereHas('registration', function ($query) use ($condition, $duration) {
                    $query->where($condition['field'], $condition['value'])
                        ->where('updated_at', '<', Carbon::now()->subDays($duration));
                })
                    ->lazy()
                    ->each(function ($user) {
                        event('eloquent.deleted: App\Models\User', $user);
                    });

                // Delete users and count the total
                $totalDeleted = User::whereHas('registration', function ($query) use ($condition, $duration) {
                    $query->where($condition['field'], $condition['value'])
                        ->where('updated_at', '<', Carbon::now()->subDays($duration));
                })->delete();

                $total += $totalDeleted;
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
