<?php

namespace App\Http\Controllers\Api;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function getAll(Request $request): JsonResponse
    {
        /** @var \App\Models\User $authUser */
        $authUser = Auth::user();

        // Check permissions
        if (! $authUser->canAny([
            PermissionEnum::VIEW_ALL_USER,
            PermissionEnum::VIEW_RELAWAN_USER,
        ])) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden',
            ], 403);
        }

        $searchQuery = $request->input('q');
        if (! $searchQuery) {
            return response()->json([
                'success' => false,
                'message' => 'No query provided',
                'data' => [],
            ], 400);
        }

        $roles = [RoleEnum::RELAWAN_BARU, RoleEnum::RELAWAN_WILAYAH];

        if ($authUser->can(PermissionEnum::VIEW_ALL_USER)) {
            $roleQuery = $request->input('role');
            if ($roleQuery) {
                $roleArray = explode(',', $roleQuery);
                $validRoles = array_intersect($roleArray, RoleEnum::values());
                if (!empty($validRoles)) {
                    $roles = $validRoles;
                }
            } else {
                $roles[] = RoleEnum::PENGURUS_WILAYAH;
            }
        }

        $usersQuery = User::select('id', 'nama')
            ->whereHas('roles', function ($query) use ($roles) {
                $query->whereIn('name', $roles);
            })
            ->where('nama', 'like', '%' . $searchQuery . '%')
            ->whereNot('id', $authUser->id);

        if ($authUser->branch_id) {
            $usersQuery->where('branch_id', $authUser->branch_id);
        }

        $users = $usersQuery->get()->map(function ($user) {
            return [
                'id' => $user->id,
                'nama' => $user->nama,
            ];
        });

        // Return success response with users data
        return response()->json([
            'success' => true,
            'message' => 'User options',
            'data' => $users,
        ], 200);
    }

    public function getRelawanForCertificate(Request $request, Event $event): JsonResponse
    {
        /** @var \App\Models\User $authUser */
        $authUser = Auth::user();

        // Check permissions
        if (! $authUser->canAny([
            PermissionEnum::VIEW_ALL_USER,
        ])) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden',
            ], 403);
        }

        $searchQuery = $request->input('q');
        if (! $searchQuery) {
            return response()->json([
                'success' => false,
                'message' => 'No query provided',
                'data' => [],
            ], 400);
        }

        $users = User::select('users.id', 'users.nama')
            ->join('event_participants', function ($join) use ($event) {
                $join->on('users.id', '=', 'event_participants.user_id')
                    ->where('event_participants.event_id', $event->id);
            })
            ->leftJoin('event_certificates', function ($join) use ($event) {
                $join->on('users.id', '=', 'event_certificates.user_id')
                    ->where('event_certificates.event_id', $event->id);
            })
            ->whereNull('event_certificates.user_id')
            ->where('users.nama', 'like', '%' . $searchQuery . '%')
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'nama' => $user->nama,
                ];
            });

        return response()->json([
            'success' => true,
            'message' => 'User options',
            'data' => $users,
        ], 200);
    }
}
