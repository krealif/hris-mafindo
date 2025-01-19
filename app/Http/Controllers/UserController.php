<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Enums\RoleEnum;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
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

        $eventCertificate = $user->certificates()->paginate(15);

        return view('hris.pengguna.profil.relawan-sertifikat', compact('user', 'eventCertificate'));
    }
}
