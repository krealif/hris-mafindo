<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Branch;
use App\Enums\RoleEnum;
use Illuminate\View\View;
use Illuminate\Support\Arr;
use App\Enums\PermissionEnum;
use App\Traits\HasUploadFile;
use App\Traits\FilterArrayInput;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\UpdateProfileRelawanRequest;
use App\Http\Requests\UpdateProfilePengurusRequest;
use Spatie\QueryBuilder\QueryBuilder;

class UserProfileController extends Controller
{
    use FilterArrayInput;
    use HasUploadFile;

    /**
     * Display a listing of the resource.
     */
    public function profile(User $user = null): View|RedirectResponse
    {
        // Memastikan agar URL tidak mengandung ID saat melihat profil diri sendiri
        if ($user && $user->is(Auth::user())) {
            return redirect()->route('user.profile');
        }

        /** @var \App\Models\User $user */
        $user = $user ?? Auth::user();

        Gate::authorize('view', $user);

        // Menentukan view berdasarkan role pengguna
        if ($user->hasRole([
            RoleEnum::RELAWAN_BARU,
            RoleEnum::RELAWAN_WILAYAH
        ])) {
            return view('hris.pengguna.profil.relawan', compact('user'));
        } elseif ($user->hasRole(RoleEnum::PENGURUS_WILAYAH)) {
            return view('hris.pengguna.profil.pengurus', compact('user'));
        }

        return view('hris.pengguna.profil.admin', compact('user'));
    }

    /**
     * Displays the list of certificates owned by the user. 
     * Only users with the specified roles can have certificates.
     */
    public function listCertificates(User $user = null): View|RedirectResponse
    {
        // Memastikan agar URL tidak mengandung ID saat melihat profil diri sendiri
        if ($user && $user->is(Auth::user())) {
            return redirect()->route('user.certificate');
        }

        /** @var \App\Models\User $user */
        $user = $user ?? Auth::user();

        Gate::authorize('view', $user);

        // Hanya relawan yang bisa memiliki sertifikat
        if (! $user->hasRole([
            RoleEnum::RELAWAN_BARU,
            RoleEnum::RELAWAN_WILAYAH
        ])) {
            abort(404);
        }

        $eventCertificate = QueryBuilder::for($user->certificates())
            ->allowedFilters('name')
            ->paginate(15)
            ->appends(request()->query());

        return view('hris.pengguna.profil.relawan-sertifikat', compact('user', 'eventCertificate'));
    }

    /**
     * Show the form to edit the user's profile.
     */
    public function editProfile(User $user = null): View|RedirectResponse
    {
        // Memastikan agar URL tidak mengandung ID saat mengedit profil diri sendiri
        if ($user && $user->is(Auth::user())) {
            return redirect()->route('user.editProfile');
        }

        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();

        /** @var \App\Models\User $user */
        $user = request()->route('user') ?? $currentUser;

        Gate::authorize('update', $user);

        // Menentukan view berdasarkan role pengguna
        if ($user->hasRole([
            RoleEnum::RELAWAN_BARU,
            RoleEnum::RELAWAN_WILAYAH
        ])) {
            if ($currentUser->hasPermissionTo(PermissionEnum::EDIT_ALL_USER)) {
                $branches = Branch::select('id', 'name')
                    ->orderBy('name', 'asc')
                    ->pluck('name', 'id');
            }

            return view('hris.pengguna.edit-profil.relawan', [
                'user' => $user,
                'userDetail' => $user->detail,
                'branches' => $branches ?? null,

            ]);
        } elseif ($user->hasRole(RoleEnum::PENGURUS_WILAYAH)) {
            return view('hris.pengguna.edit-profil.pengurus', compact('user'));
        }

        abort(404);
    }

    /**
     * Update the user's profile based on their role.
     */
    public function updateProfile(User $user = null): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = $user ?? Auth::user();

        Gate::authorize('update', $user);

        // Menentukan view berdasarkan role pengguna
        if ($user->hasRole([
            RoleEnum::RELAWAN_BARU,
            RoleEnum::RELAWAN_WILAYAH
        ])) {
            return $this->updateProfileRelawan(app(UpdateProfileRelawanRequest::class), $user);
        } elseif ($user->hasRole(RoleEnum::PENGURUS_WILAYAH)) {
            return $this->updateProfilePengurus(app(UpdateProfilePengurusRequest::class), $user);
        }

        abort(404);
    }

    /**
     * Update the profile of a "Relawan" user.
     */
    public function updateProfileRelawan(UpdateProfileRelawanRequest $request, User $user): RedirectResponse
    {
        $validated = $request->validated();

        // Filter untuk menghapus entri kosong dalam array
        $validated = $this->filterArrayInput($validated, [
            'medsos',
            'pendidikan',
            'pekerjaan',
            'sertifikat',
        ]);

        if ($request->hasFile('foto')) {
            $path = $this->uploadFile('profile', $validated['foto'], 'public');
            $validated['foto'] = $path;
        }

        $user->update(
            Arr::only($validated, [
                'nama',
                'no_relawan',
                'foto',
                'branch_id',
            ])
        );

        $user->detail()->update(
            Arr::except($validated, [
                'nama',
                'no_relawan',
                'foto',
                'branch_id',
            ])
        );

        flash()->success("Berhasil. Profil telah diperbarui");

        if ($user->is(Auth::user())) {
            return to_route('user.profile');
        }

        return to_route('user.profile', $user->id);
    }

    /**
     * Update the profile of a "Pengurus" user.
     */
    public function updateProfilePengurus(UpdateProfilePengurusRequest $request, User $user): RedirectResponse
    {
        $validated = $request->validated();

        // Filter untuk menghapus entri kosong dalam array
        $validated = $this->filterArrayInput($validated, ['staff']);

        $user->update([
            'nama' => $validated['coordinatorName'],
        ]);

        Branch::where('id', $user->branch_id)
            ->update(Arr::only($validated, ['staff']));

        flash()->success("Berhasil. Profil telah diperbarui");

        if ($user->is(Auth::user())) {
            return to_route('user.profile');
        }

        return to_route('user.profile', $user->id);
    }
}
