<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Branch;
use App\Models\TempUser;
use Illuminate\View\View;
use App\Models\UserDetail;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use App\Traits\HandlesArrayInput;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Requests\StoreRegistrationRelawanRequest;

class RegistrationMigrationController extends Controller
{
    use HandlesArrayInput;

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $users = QueryBuilder::for(TempUser::class)
            ->allowedFilters(['nama', 'email', 'no_relawan'])
            ->orderBy('updated_at', 'desc')
            ->with('branch')
            ->paginate(20)
            ->appends(request()->query());

        return view('hris.migrasi-data.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $branches = Branch::select('id', 'nama')
            ->orderBy('nama', 'asc')
            ->pluck('nama', 'id');

        return view('hris.migrasi-data.form-migrasi', compact('branches'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRegistrationRelawanRequest $request): RedirectResponse
    {
        $validated = $request->validate([
            ...$request->rules(),
            'nama' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(TempUser::class, 'email'),
                Rule::unique(User::class, 'email'),
            ],
            'no_relawan' => ['nullable', 'string', Rule::unique(User::class, 'no_relawan')],
        ]);

        $validated = $this->handleArrayField($validated, [
            'pendidikan',
            'pekerjaan',
            'sertifikat'
        ]);

        DB::transaction(function () use ($validated) {
            $detail = Arr::except($validated, [
                'nama',
                'email',
                'no_relawan',
                'branch_id',
                'mode'
            ]);

            $userDetail = UserDetail::create([...$detail]);

            $tempData = Arr::only($validated, [
                'nama',
                'email',
                'no_relawan',
                'branch_id'
            ]);

            $tempData['user_detail_id'] = $userDetail->id;

            TempUser::create($tempData);
        });

        flash()->success("Berhasil. Relawan [{$validated['nama']}] telah ditambahkan.");
        return to_route('migrasi.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TempUser $user): View
    {
        $branches = Branch::select('id', 'nama')
            ->orderBy('nama', 'asc')
            ->pluck('nama', 'id');

        $detail = $user->userDetail;

        return view('hris.migrasi-data.form-migrasi', compact(
            'user',
            'detail',
            'branches'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreRegistrationRelawanRequest $request, TempUser $user): RedirectResponse
    {
        $validated = $request->validate([
            ...$request->rules(),
            'nama' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(TempUser::class, 'email')->ignore($user->id),
                Rule::unique(User::class, 'email')->ignore($user->id),
            ],
            'no_relawan' => ['nullable', 'string', Rule::unique(User::class, 'no_relawan')->ignore($user->id)],
        ]);

        $validated = $this->handleArrayField($validated, [
            'pendidikan',
            'pekerjaan',
            'sertifikat'
        ]);

        DB::transaction(function () use ($validated, $user) {
            $tempData = Arr::only($validated, [
                'nama',
                'email',
                'no_relawan',
                'branch_id'
            ]);

            $user->update($tempData);

            $detail = Arr::except($validated, [
                'nama',
                'email',
                'no_relawan',
                'branch_id',
                'mode'
            ]);

            $user->userDetail?->update([...$detail]);
        });

        flash()->success("Berhasil. Relawan [{$validated['nama']}] telah diperbarui.");
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TempUser $user): RedirectResponse
    {
        $userName = $user->nama;

        DB::transaction(function () use ($user) {
            UserDetail::where('id', $user->id)
                ->whereNull('user_id')
                ->delete();

            $user->delete();
        });

        flash()->success("Berhasil. Relawan [{$userName}] telah dihapus.");

        if (url()->previous() != route('migrasi.index')) {
            return to_route('migrasi.index');
        }

        return back();
    }
}
