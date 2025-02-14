<?php

namespace App\Http\Controllers;

use Closure;
use App\Models\User;
use App\Enums\RoleEnum;
use App\Models\TempUser;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\Routing\Controllers\HasMiddleware;

class SuperAdminController extends Controller implements HasMiddleware
{
    // Solusi sementara untuk menangani aksi yang hanya bisa dilakukan oleh super admin.
    // TODO: Menggunakan Spatie Permission untuk menangani hak akses admin dan super admin.

    public static function middleware(): array
    {
        return [
            function (Request $request, Closure $next) {
                // Saat ini super admin adalah user role 'ADMIN' dengan id 1.
                if (Auth::id() == 1) {
                    return $next($request);
                }

                abort(403);
            },
        ];
    }

    public function panel(): View
    {
        return view('hris.super-admin.panel');
    }

    public function createAdmin(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'max:255',
                Rule::email()
                    ->rfcCompliant(strict: true)
                    ->preventSpoofing(),
                Rule::unique(User::class),
                Rule::unique(TempUser::class),
            ],
            'password' => ['required', 'string', Password::default()]
        ]);

        DB::transaction(function () use ($validated) {
            $admin = User::create([
                ...$validated,
                'password' => Hash::make($validated['password']),
                'is_approved' => true,
            ]);

            $admin->assignRole(RoleEnum::ADMIN);
        });

        flash()->success("Berhasil. Admin [{$validated['nama']}] telah ditambahkan.");
        return to_route('superadmin.panel');
    }

    public function destroyUser(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'string', 'max:255'],
            'confirmation' => ['required', 'string', 'max:255']
        ]);

        /** @var \App\Models\User $user */
        $user = User::findOrFail($validated['user_id']);
        $userName = strtolower($user->nama ?? '');

        if (! (strtolower($validated['confirmation']) == $userName)) {
            throw ValidationException::withMessages([
                'confirmation' => ['Nama pengguna yang dituliskan berbeda dengan yang akan dihapus'],
            ]);
        }

        $user->delete();

        flash()->success("Berhasil. Admin [{$userName}] telah dihapus.");
        return to_route('superadmin.panel');
    }
}
