<?php

namespace App\Http\Controllers;

use App\Enums\LetterStatusEnum;
use App\Enums\RoleEnum;
use App\Filters\FilterDate;
use App\Filters\FilterLetterType;
use App\Filters\FilterRelawanWilayahLetter;
use App\Http\Requests\StoreLetterRequest;
use App\Models\Letter;
use App\Traits\HasUploadFile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class LetterController extends Controller
{
    use HasUploadFile;

    /**
     * Display a listing of the letters created by the current user or assigned to them.
     */
    public function indexLetter(): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            abort(403);
        }

        $letters = QueryBuilder::for(Letter::class)
            ->allowedFilters([
                'title',
                AllowedFilter::custom('type', new FilterLetterType),
                AllowedFilter::custom('updated_at', new FilterDate),
            ])
            ->where(function ($query) use ($user) {
                $query->whereBelongsTo($user, 'createdBy')
                    ->orWhereHas('recipients', function ($query) use ($user) {
                        $query->where('user_id', $user->id)
                            ->where('status', LetterStatusEnum::SELESAI);
                    });
            })
            ->with('recipients')
            ->latest('updated_at')
            ->paginate(15);

        // Periksa apakah ada permohonan surat yang dibuat/diajukan oleh orang lain
        $createdByOthers = $letters->where('created_by', '!=', $user->id)->isNotEmpty();
        if ($createdByOthers) {
            // Eager load hanya jika ada data dari orang lain
            /** @var \App\Models\Letter $letters */
            $letters->load('createdBy.roles');
        }

        return view('hris.surat.user.index', compact('letters'));
    }

    /**
     * Display a listing of letters filtered by wilayah (branch) of currently logged in PENGURUS.
     */
    public function indexByWilayah(): View
    {
        Gate::authorize('viewByWilayah', Letter::class);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $letters = QueryBuilder::for(Letter::class)
            ->allowedFilters([
                'title',
                AllowedFilter::custom('relawan', new FilterRelawanWilayahLetter),
                AllowedFilter::custom('updated_at', new FilterDate),
            ])
            ->where('created_by', '!=', $user->id)
            ->where(function ($query) use ($user) {
                $query->whereHas('recipients', function ($query) use ($user) {
                    $query->where('user_id', '!=', $user->id)
                        ->where('users.branch_id', $user->branch_id)
                        ->where('status', LetterStatusEnum::SELESAI);
                })
                    ->orWhereHas('createdBy', function ($query) use ($user) {
                        $query->where('users.branch_id', $user->branch_id)
                            ->where('status', LetterStatusEnum::SELESAI);
                    });
            })
            ->with('recipients')
            ->latest('updated_at')
            ->paginate(15);

        return view('hris.surat.user.index-wilayah', compact('letters'));
    }

    /**
     * Show the form for creating a new letter.
     */
    public function create(): View
    {
        Gate::authorize('create', Letter::class);

        return view('hris.surat.create');
    }

    /**
     * Store a newly created letter in storage.
     * If an attachment is included, it is uploaded.
     */
    public function store(StoreLetterRequest $request): RedirectResponse
    {
        Gate::authorize('create', Letter::class);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $validated = $request->validated();

        if ($request->hasFile('attachment')) {
            $path = $this->uploadFile('lampiran', $validated['attachment']);
            $validated['attachment'] = $path;
        }

        $letter = Letter::create([
            'created_by' => Auth::id(),
            // Jika admin yang membuat, status permohonan surat langsung menjadi DIPROSES
            'status' => $user->hasRole(RoleEnum::ADMIN)
                ? LetterStatusEnum::DIPROSES
                : LetterStatusEnum::MENUNGGU,
            ...$validated,
        ]);

        // Menambahkan tujuan permohonan surat (recipients) jika ada dalam request
        if (array_key_exists('recipients', $validated)) {
            $letter->recipients()->attach($validated['recipients']);
        }

        if ($user->hasRole(RoleEnum::ADMIN)) {
            flash()->success("Berhasil. Permohonan Surat [{$validated['title']}] telah dibuat.");

            return to_route('surat.index');
        }

        flash()->success("Berhasil. Permohonan Surat [{$validated['title']}] telah dibuat. Admin akan segera meninjau dan memprosesnya.");

        return to_route('surat.letterbox');
    }

    /**
     * Display the details of a specific letter.
     */
    public function show(Letter $letter): View
    {
        Gate::authorize('view', $letter);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Jika admin membuka detail permohonan surat, status akan diubah menjadi DIPROSES,
        // yang berarti permohonan surat sudah "terkunci" dan tidak bisa diubah atau dihapus lagi.
        if ($user->hasRole('admin')) {
            if ($letter->status == LetterStatusEnum::MENUNGGU) {
                $letter->update(['status' => LetterStatusEnum::DIPROSES]);
            }

            return view('hris.surat.admin.detail', compact('letter'));
        }

        return view('hris.surat.user.detail', compact('letter'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Letter $letter): View
    {
        Gate::authorize('update', $letter);

        return view('hris.surat.user.edit', compact('letter'));
    }

    /**
     * Update the specified letter in storage.
     */
    public function update(StoreLetterRequest $request, Letter $letter): RedirectResponse
    {
        Gate::authorize('update', $letter);

        $validated = $request->validated();

        // Jika ada permintaan untuk menghapus lampiran, hapus file yang lama
        if (
            $request->boolean('_isDeleteAttachment')
            && $letter->attachment
        ) {
            $this->deleteFile($letter->attachment);
            $validated['attachment'] = null;
        }

        if ($request->hasFile('attachment')) {
            $path = $this->uploadFile('lampiran', $validated['attachment']);
            $validated['attachment'] = $path;
        }

        $letter->updated_at = \Carbon\Carbon::now();
        $letter->update([
            ...$validated,
            'status' => LetterStatusEnum::MENUNGGU,
        ]);

        // Memperbarui tujuan permohonan surat (recipients)
        if (array_key_exists('recipients', $validated)) {
            $letter->recipients()->sync($validated['recipients']);
        }

        flash()->success("Berhasil. Permohonan Surat [{$validated['title']}] telah diperbarui. Admin akan segera meninjau dan memprosesnya");

        return to_route('surat.show', $letter->id);
    }

    /**
     * Remove the specified letter from storage.
     */
    public function destroy(Letter $letter): RedirectResponse
    {
        Gate::authorize('delete', $letter);

        $letterTitle = $letter->title;
        $letter->delete();

        flash()->success("Berhasil. Permohonan Surat [{$letterTitle}] telah dihapus.");

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $redirectRoute = $user->hasRole('admin') ? 'surat.indexHistory' : 'surat.letterbox';

        $prevUrlQuery = parse_url(url()->previous(), PHP_URL_QUERY);
        if (url()->previous() == route($redirectRoute, $prevUrlQuery)) {
            return to_route($redirectRoute, $prevUrlQuery);
        }

        return to_route($redirectRoute);
    }

    /**
     * Download the result file of the specified letter.
     * A temporary URL is generated for the file.
     */
    public function download(Letter $letter): RedirectResponse
    {
        Gate::authorize('download', $letter);

        if (! $letter->result_file) {
            return abort(404);
        }

        $url = Storage::temporaryUrl(
            $letter->result_file,
            now()->addMinutes(60)
        );

        return redirect($url);
    }

    /**
     * Download the attachment of the specified letter submission.
     * A temporary URL is generated for the attachment.
     */
    public function downloadAttachment(Letter $letter): RedirectResponse
    {
        Gate::authorize('view', $letter);

        if (! $letter->attachment) {
            return abort(404);
        }

        $url = Storage::temporaryUrl(
            $letter->attachment,
            now()->addMinutes(60)
        );

        return redirect($url);
    }
}
