<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use Illuminate\Http\Request;
use App\Enums\RegistrationTypeEnum;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\StoreRegistrationRequest;
use Illuminate\Http\RedirectResponse;

class RegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function selectForm(): RedirectResponse | View
    {
        $registration = Auth::user()->registration;
        if ($registration) {
            return to_route('registration.showForm', $registration->type);
        }

        return view('hris.registrasi.form-selection');
    }

    /**
     * Show the form for submitting registration details.
     */
    public function showForm(string $type): View
    {
        Gate::authorize('create', [Registration::class, $type]);

        // Check if the form is in the available list
        if (!in_array($type, RegistrationTypeEnum::values())) {
            abort(404);
        }

        return view('hris.registrasi.form', compact('type'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRegistrationRequest $request, string $type)
    {
        Gate::authorize('create', [Registration::class, $type]);

        dd($request->all());
        return to_route('registration.showForm', $type);
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
}
