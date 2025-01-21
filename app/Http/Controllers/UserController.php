<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Branch;
use App\Enums\RoleEnum;
use Illuminate\View\View;
use App\Filters\FilterRole;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class UserController extends Controller
{
    public function index(): View
    {
        Gate::authorize('viewAny', User::class);

        $users = QueryBuilder::for(User::class)
            ->allowedFilters([
                'nama',
                AllowedFilter::custom('role', new FilterRole),
                'email',
                AllowedFilter::exact('branch_id'),
                'no_relawan'
            ])
            ->select(['id', 'nama', 'email', 'no_relawan', 'branch_id'])
            ->where('is_approved', true)
            ->with('branch', 'roles')
            ->orderBy('nama')
            ->paginate(15)
            ->appends(request()->query());

        $branches = Branch::select('id', 'name')
            ->orderBy('name', 'asc')
            ->pluck('name', 'id');

        // Query to count users by role
        $roleCounts = DB::table('model_has_roles')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->where('model_has_roles.model_type', User::class)
            ->groupBy('roles.name')
            ->select('roles.name as role_name', DB::raw('count(*) as count'))
            ->get();

        return view('hris.pengguna.index', compact('users', 'branches', 'roleCounts'));
    }

    public function indexWilayah(): View
    {
        Gate::authorize('view-relawan-user');

        /** @var \App\Models\User $authUser */
        $user = Auth::user();

        $users = QueryBuilder::for(User::class)
            ->allowedFilters([
                'nama',
                AllowedFilter::custom('role', new FilterRole),
                'email',
                'no_relawan'
            ])
            ->select(['id', 'nama', 'email', 'no_relawan'])
            ->role([RoleEnum::RELAWAN_BARU, RoleEnum::RELAWAN_WILAYAH])
            ->where('branch_id', $user->branch_id)
            ->where('is_approved', true)
            ->with('branch', 'roles')
            ->orderBy('nama')
            ->paginate(15)
            ->appends(request()->query());

        return view('hris.pengguna.index-wilayah', compact('users'));
    }
}
