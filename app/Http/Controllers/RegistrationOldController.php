<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Branch;
use Illuminate\View\View;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\RedirectResponse;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Http\Requests\StoreRegistrationRequest;
use Illuminate\Support\Facades\App;

class RegistrationOldController extends Controller
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

        return view('hris.account.registration', [
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

        return to_route('register.success')
            ->with('success', 'page');
    }

    /**
     * Display registration success status.
     */
    public function success(): View | RedirectResponse
    {
        if (session('success')) {
            return view('auth.register-success');
        }

        return to_route('login');
    }

    /**
     * Accept user registration.
     */
    public function accept(Registration $registration, Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'role' => ['in:relawan,pengurus'],
        ]);

        $userData = $registration->replicate()->toArray();
        $userData['password'] = $registration->password;

        $user = new User($userData);
        $user->assignRole($validated['role']);

        DB::transaction(function () use ($user, $registration) {
            $user->save();
            $registration->delete();
        });

        Mail::to($registration->email)
            ->send(new \App\Mail\RegistrationAccepted($registration->name));

        flash()->success("Berhasil! Pendaftaran a.n. [{$registration->name}] telah diterima.");
        return to_route('registration.index', session('q.registration'));
    }

    /**
     * Reject user registration.
     */
    public function reject(Registration $registration, Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'message' => ['string'],
        ]);

        $name = $registration->name;
        Mail::to($registration->email)
            ->send(new \App\Mail\RegistrationRejected($name, $validated['message']));

        $registration->delete();

        flash()->success("Berhasil! Pendaftaran a.n. [{$registration->name}] telah ditolak.");
        return to_route('registration.index', session('q.registration'));
    }
}
