<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMigrationRelawanRequest;
use App\Http\Requests\UpdateMigrationRelawanRequest;
use App\Models\Branch;
use App\Models\TempUser;
use App\Models\UserDetail;
use App\Traits\HandlesArrayInput;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Spatie\QueryBuilder\QueryBuilder;

class UserMigrationController extends Controller
{
    use HandlesArrayInput;

    /**
     * Display a listing of the temporary users.
     */
    public function index(): View
    {
        $tempUsers = QueryBuilder::for(TempUser::class)
            ->allowedFilters(['nama', 'email', 'no_relawan'])
            ->orderBy('updated_at', 'desc')
            ->with('branch')
            ->paginate(15)
            ->appends(request()->query());

        return view('hris.migrasi-user.index', compact('tempUsers'));
    }

    /**
     * Show the form for creating a new temporary user.
     */
    public function create(): View
    {
        $branches = Branch::select('id', 'nama')
            ->orderBy('nama', 'asc')
            ->pluck('nama', 'id');

        return view('hris.migrasi-user.form-migrasi', compact('branches'));
    }

    /**
     * Store a newly created temporary user in storage.
     */
    public function store(StoreMigrationRelawanRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $validated = $this->handleArrayField($validated, [
            'pendidikan',
            'pekerjaan',
            'sertifikat',
        ]);

        DB::transaction(function () use ($validated) {
            $detail = Arr::except($validated, [
                'nama',
                'email',
                'no_relawan',
                'branch_id',
                'mode',
            ]);

            $userDetail = UserDetail::create([...$detail]);

            $tempData = Arr::only($validated, [
                'nama',
                'email',
                'no_relawan',
                'branch_id',
            ]);

            $tempData['user_detail_id'] = $userDetail->id;

            TempUser::create($tempData);
        });

        flash()->success("Berhasil. Relawan [{$validated['nama']}] telah ditambahkan.");

        return to_route('migrasi.index');
    }

    /**
     * Show the form for editing the specified temporary user.
     */
    public function edit(TempUser $tempUser): View
    {
        $branches = Branch::select('id', 'nama')
            ->orderBy('nama', 'asc')
            ->pluck('nama', 'id');

        $detail = $tempUser->detail;

        return view('hris.migrasi-user.form-migrasi', compact(
            'tempUser',
            'detail',
            'branches'
        ));
    }

    /**
     * Update the specified temporary user in storage.
     */
    public function update(UpdateMigrationRelawanRequest $request, TempUser $tempUser): RedirectResponse
    {
        $validated = $request->validated();

        $validated = $this->handleArrayField($validated, [
            'pendidikan',
            'pekerjaan',
            'sertifikat',
        ]);

        DB::transaction(function () use ($validated, $tempUser) {
            $tempData = Arr::only($validated, [
                'nama',
                'email',
                'no_relawan',
                'branch_id',
            ]);

            $tempUser->update($tempData);

            $detail = Arr::except($validated, [
                'nama',
                'email',
                'no_relawan',
                'branch_id',
                'mode',
            ]);

            $tempUser->detail?->update([...$detail]);
        });

        flash()->success("Berhasil. Relawan [{$validated['nama']}] telah diperbarui.");

        return to_route('migrasi.edit');
    }

    /**
     * Remove the specified temporary user from storage.
     */
    public function destroy(TempUser $tempUser): RedirectResponse
    {
        $userName = $tempUser->nama;

        DB::transaction(function () use ($tempUser) {
            UserDetail::where('id', $tempUser->id)
                ->whereNull('user_id')
                ->delete();

            $tempUser->delete();
        });

        flash()->success("Berhasil. Relawan [{$userName}] telah dihapus.");

        if ($q = parse_url(url()->previous(), PHP_URL_QUERY))
            return to_route('migrasi.index', $q);

        return to_route('migrasi.index');
    }
}
