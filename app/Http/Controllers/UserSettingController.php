<?php

namespace App\Http\Controllers;

use App\Enums\RoleEnum;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class UserSettingController extends Controller
{
    public function settings(): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        return view('hris.pengguna.settings', compact('user'));
    }

    public function updateEmail(Request $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Hanya relawan yang boleh mengganti emailnya
        if ($user->hasRole([
            RoleEnum::ADMIN,
            RoleEnum::PENGURUS_WILAYAH
        ])) {
            abort(403);
        }

        $validated = $request->validate([
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('temp_users'),
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => ['required', 'string', 'current_password:web'],
        ]);

        if ($validated['email'] == $user->email) {
            throw ValidationException::withMessages([
                'email' => ['Email baru tidak boleh sama dengan email saat ini.'],
            ]);
        }

        // Saat ini, penggantian email tidak memerlukan kode verifikasi
        $user->forceFill([
            'email' => $validated['email'],
        ])->save();

        flash()->success('Berhasil. Email telah diperbarui.');

        return to_route('user.settings');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => ['required', 'string', 'current_password:web'],
            'new_password' => ['required', 'string', Password::default(), 'confirmed'],
        ]);

        if (Hash::check($validated['new_password'], $user->password)) {
            throw ValidationException::withMessages([
                'new_password' => ['Password baru tidak boleh sama dengan password saat ini.'],
            ]);
        }

        $user->forceFill([
            'password' => $validated['new_password'],
        ])->save();

        flash()->success('Berhasil. Password telah diperbarui.');

        return to_route('user.settings');
    }
}
