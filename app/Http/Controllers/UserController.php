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
use Spatie\SimpleExcel\SimpleExcelWriter;

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

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $users = QueryBuilder::for(User::class)
            ->allowedFilters([
                'nama',
                AllowedFilter::custom('role', new FilterRole),
                'email',
                'no_relawan'
            ])
            ->select(['id', 'nama', 'email', 'no_relawan'])
            ->where('branch_id', $user->branch_id)
            ->whereHas('roles', function ($query) {
                $query->whereIn('name', [RoleEnum::RELAWAN_BARU, RoleEnum::RELAWAN_WILAYAH]);
            })
            ->where('is_approved', true)
            ->with('branch', 'roles')
            ->orderBy('nama')
            ->paginate(15)
            ->appends(request()->query());

        $roleCounts = DB::table('model_has_roles')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->join('users', 'model_has_roles.model_id', '=', 'users.id')
            ->where('users.branch_id', $user->branch_id)
            ->select('roles.name as role_name', DB::raw('count(*) as count'))
            ->groupBy('roles.name')
            ->get();

        return view('hris.pengguna.index-wilayah', compact('users', 'roleCounts'));
    }

    public function export(): void
    {
        Gate::authorize('viewAny', User::class);

        $users = User::whereHas('roles', function ($query) {
            $query->whereIn('name', [RoleEnum::PENGURUS_WILAYAH]);
        })
            ->where('is_approved', true)
            ->with('branch')
            ->lazy();

        $timestamp = time();
        $filename = "data-pengurus-{$timestamp}.csv";
        $writer = SimpleExcelWriter::streamDownload($filename);

        foreach ($users as $user) {
            $writer->addRow([
                'Nama' => $user->nama,
                'Email' => $user->email,
                'Wilayah' => $user->branch?->name,
            ]);
        }

        $writer->toBrowser();
    }

    public function exportRelawan(): void
    {
        Gate::authorize('viewAny', User::class);

        $users = QueryBuilder::for(User::class)
            ->allowedFilters([
                AllowedFilter::exact('branch_id'),
            ])
            ->whereHas('roles', function ($query) {
                $query->whereIn('name', [RoleEnum::RELAWAN_BARU, RoleEnum::RELAWAN_WILAYAH]);
            })
            ->where('is_approved', true)
            ->with('detail', 'branch', 'roles')
            ->orderBy('nama')
            ->lazy(500);

        $timestamp = time();
        $filename = "data-relawan-{$timestamp}.csv";
        $writer = SimpleExcelWriter::streamDownload($filename);

        /** @var \App\Models\User $user */
        foreach ($users as $user) {
            $userDetail = $user->detail;

            $pendidikan = collect((array) $userDetail?->pendidikan)->map(function ($p) {
                return "[{$p->tingkat}]-{$p->institusi} {$p->jurusan}";
            })->implode(', ');

            $pekerjaan = collect((array) $userDetail?->pekerjaan)->map(function ($p) {
                return "[{$p->jabatan}]-{$p->lembaga} ({$p->tahun})";
            })->implode(', ');

            $sertifikat = collect((array) $userDetail?->sertifikat)->map(function ($s) {
                return "{$s->nama} ({$s->masa})";
            })->implode(', ');

            $medsos = collect((array) $userDetail?->medsos)->map(function ($value, $key) {
                $platform = ucfirst($key);
                return "[{$platform}]: {$value}";
            })->implode(', ');

            $writer->addRow([
                'No. Relawan' => $user->no_relawan,
                'Nama' => $user->nama,
                'Panggilan' => $userDetail?->panggilan,
                'Role' => $user->role?->label(),
                'Email' => $user->email,
                'Jenis Kelamin' => $userDetail?->gender?->label(),
                'Tanggal Lahir' => $userDetail?->tgl_lahir?->format('Y-m-d'),
                'Agama' => $userDetail?->agama?->label(),
                'Wilayah' => $user->branch?->name,
                'Alamat' => $userDetail?->alamat,
                'Disabilitas' => $userDetail?->disabilitas,
                'No. Whatsapp' => $userDetail?->no_wa,
                'No. HP' => $userDetail?->no_hp,
                'Bidang Keahlian' => $userDetail?->bidang_keahlian,
                'Bidang Mafindo' => $userDetail?->bidang_mafindo?->label(),
                'Tahun Bergabung' => $userDetail?->thn_bergabung,
                'PDR' => $userDetail?->pdr,
                'Medsos' => $medsos,
                'Riwayat Pendidikan' => $pendidikan,
                'Riwayat Pekerjaan' => $pekerjaan,
                'Sertifikat' => $sertifikat,
            ]);
        }

        $writer->toBrowser();
    }
}
