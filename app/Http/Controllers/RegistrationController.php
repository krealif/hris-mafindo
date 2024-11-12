<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\View\View;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Routing\Controllers\Middleware;
use App\Http\Requests\StoreRegistrationRequest;
use Illuminate\Routing\Controllers\HasMiddleware;

class RegistrationController extends Controller implements HasMiddleware
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $users = QueryBuilder::for(Registration::class)
            ->allowedFilters([
                'name',
                'email',
                'member_number',
                AllowedFilter::exact('branch_id')
            ])
            ->with('branch')
            ->paginate(20, ['id', 'name', 'email', 'member_number', 'branch_id'])
            ->appends(request()->query());

        return view('hris.registration', [
            'users' => $users,
            'branches' => Branch::all(['id', 'name']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('auth.register', [
            'branches' => Branch::all(['id', 'name']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRegistrationRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        Registration::create($validated);

        return to_route('register.success')->with('status', 'success');
    }

    /**
     * Display registration success status
     */
    public function success(): View | RedirectResponse
    {
        if (session('status')) {
            return view('auth.register-success');
        }

        return to_route('login');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('preserveUrlQuery', only: ['index']),
        ];
    }
}
