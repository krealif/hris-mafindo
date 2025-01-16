<?php

namespace App\Http\Controllers;

use App\Enums\RoleEnum;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function profile(): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->hasRole([
            RoleEnum::RELAWAN_BARU,
            RoleEnum::RELAWAN_WILAYAH
        ])) {
            return view('hris.profil.relawan', compact('user'));
        } elseif ($user->hasRole(RoleEnum::PENGURUS_WILAYAH)) {
            return view('hris.profil.pengurus', compact('user'));
        }

        return view('hris.profil.admin', compact('user'));
    }
}
