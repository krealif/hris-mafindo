<?php

namespace App\Http\Controllers\Api;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        /** @var \App\Models\User $authUser */
        $authUser = Auth::user();

        // Check permissions
        if (
            ! ($authUser->can(PermissionEnum::CREATE_LETTER_FOR_RELAWAN) ||
                $authUser->can(PermissionEnum::CREATE_LETTER_FOR_PENGURUS))
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden',
            ], 403);
        }

        $query = $request->input('q');
        if (! $query) {
            return response()->json([
                'success' => false,
                'message' => 'No query provided',
                'data' => [],
            ], 400);
        }

        $roles = [RoleEnum::RELAWAN_BARU, RoleEnum::RELAWAN_WILAYAH];
        if ($authUser->hasRole(RoleEnum::ADMIN)) {
            $roles[] = RoleEnum::PENGURUS_WILAYAH;
        }

        $users = User::select('id', 'nama')
            ->role($roles)
            ->where('nama', 'like', '%'.$query.'%')
            ->whereNot('id', $authUser->id)
            ->get();

        $options = $users->map(function ($user) {
            return [
                'id' => $user->id,
                'text' => $user->nama,
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'User options',
            'data' => $options,
        ], 200);
    }
}
