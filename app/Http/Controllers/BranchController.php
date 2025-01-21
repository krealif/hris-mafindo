<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Spatie\QueryBuilder\QueryBuilder;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $branches = QueryBuilder::for(Branch::class)
            ->allowedFilters('name')
            ->orderBy('name')
            ->withCount(['users'])
            ->paginate(15)
            ->appends(request()->query());

        return view('hris.wilayah.index', compact('branches'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('hris.wilayah.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'unique:branches', 'max:255']
        ]);

        Branch::create($validated);

        flash()->success("Berhasil. Wilayah [{$validated['name']}] telah ditambahkan.");
        return to_route('wilayah.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Branch $branch): View
    {
        $users = $branch->users()
            ->leftJoin('model_has_roles', function ($join) {
                $join->on('model_has_roles.model_id', '=', 'users.id')
                    ->where('model_has_roles.model_type', '=', 'app\Models\User');
            })
            ->with('roles')
            ->orderBy('model_has_roles.role_id')
            ->paginate(15);

        $roleCounts = DB::table('model_has_roles')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->join('users', 'model_has_roles.model_id', '=', 'users.id')
            ->where('users.branch_id', $branch->id)
            ->select('roles.name as role_name', DB::raw('count(*) as count'))
            ->groupBy('roles.name')
            ->get();

        return view('hris.wilayah.detail', compact('branch', 'users', 'roleCounts'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Branch $branch): View
    {
        return view('hris.wilayah.edit', compact('branch'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Branch $branch): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'unique:branches', 'max:255']
        ]);

        $branch->update($validated);

        flash()->success("Berhasil. Wilayah [{$validated['name']}] telah diperbarui.");
        return to_route('wilayah.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Branch $branch): RedirectResponse
    {
        if ($branch->users()->count() > 0) {
            abort(403);
        }

        $branch->delete();

        flash()->success("Berhasil. Wilayah [{$branch->name}] telah diperbarui.");

        $prevUrlQuery = parse_url(url()->previous(), PHP_URL_QUERY);
        if (url()->previous() == route('wilayah.index', $prevUrlQuery)) {
            return to_route('wilayah.index', $prevUrlQuery);
        }

        return to_route('wilayah.index');
    }
}
