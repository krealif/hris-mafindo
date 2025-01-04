<?php

namespace App\Http\Controllers;

use App\Models\Letter;
use App\Enums\RoleEnum;
use Illuminate\View\View;
use App\Enums\PermissionEnum;
use App\Traits\HasUploadFile;
use App\Enums\LetterStatusEnum;
use App\Rules\ValidRelawanRule;
use App\Http\Requests\LetterRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\RedirectResponse;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Rules\ValidPengurusRelawanRule;
use Illuminate\Support\Facades\Storage;

class LetterController extends Controller
{
    use HasUploadFile;

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            abort(403);
        }

        $letters = QueryBuilder::for(Letter::whereBelongsTo($user, 'createdBy')
            ->orWhereHas('recipients', function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->where('status', LetterStatusEnum::SELESAI);
            }))
            ->allowedFilters(['title'])
            ->with('recipients')
            ->latest('updated_at')
            ->paginate(15);

        // Periksa apakah ada letter yang dibuat/diajukan oleh orang lain
        $createdByOthers = $letters->where('created_by', '!=', $user->id)->isNotEmpty();
        if ($createdByOthers) {
            // Eager load hanya jika ada data dari orang lain

            /** @var \App\Models\Letter $letters */
            $letters->load('createdBy.roles');
        }

        return view('hris.surat.user.index', compact('letters'));
    }

    /**
     * Display a listing of the resource.
     */
    public function indexByWilayah(): View
    {
        Gate::authorize('viewByWilayah', Letter::class);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $letters = Letter::where('created_by', '!=', $user->id)
            ->whereHas('recipients', function ($query) use ($user) {
                $query->where('user_id', '!=', $user->id)
                    ->where('users.branch_id', $user->branch_id)
                    ->where('status', LetterStatusEnum::SELESAI);
            })
            ->orWhereHas('createdBy', function ($query) use ($user) {
                $query->where('users.branch_id', $user->branch_id)
                    ->where('status', LetterStatusEnum::SELESAI);
            })
            ->latest('updated_at')
            ->paginate(15);


        return view('hris.surat.user.index-wilayah', compact('letters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        Gate::authorize('create', Letter::class);

        return view('hris.surat.user.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LetterRequest $request): RedirectResponse
    {
        Gate::authorize('create', Letter::class);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $validated = $request->validated();

        if ($request->hasFile('attachment')) {
            $path = $this->uploadFile('berkas', $validated['attachment']);
            $validated['attachment'] = $path;
        }

        $letter = Letter::create([
            'created_by' => Auth::id(),
            'status' => $user->hasRole(RoleEnum::ADMIN)
                ? LetterStatusEnum::DIPROSES
                : LetterStatusEnum::MENUNGGU,
            ...$validated
        ]);

        if (
            $user->hasRole('admin') || $request->boolean('_withRecipient')
            && $user->canAny(
                [
                    PermissionEnum::CREATE_LETTER_FOR_RELAWAN,
                    PermissionEnum::CREATE_LETTER_FOR_PENGURUS
                ]
            )
        ) {
            $request->validate([
                'recipients' => ['required', 'array', 'max:10'],
                'recipients.*' => [
                    $user->hasRole(RoleEnum::ADMIN)
                        ? new ValidPengurusRelawanRule()
                        : new ValidRelawanRule()
                ]
            ]);

            $letter->recipients()->attach($request->recipients);
        }

        flash()->success("Berhasil. Ajuan Surat [{$validated['title']}] telah dibuat. Admin akan segera meninjau dan memprosesnya.");

        return to_route('surat.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Letter $letter): View
    {
        Gate::authorize('view', $letter);

        /** @var \App\Models\User $user */
        $user = Auth::user();

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
     * Update the specified resource in storage.
     */
    public function update(LetterRequest $request, Letter $letter): RedirectResponse
    {
        Gate::authorize('update', $letter);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $validated = $request->validated();

        if (
            $request->boolean('_isDeleteAttachment')
            && $letter->attachment
        ) {
            $this->deleteFile($letter->attachment);
            $validated['attachment'] = null;
        }

        if ($request->hasFile('attachment')) {
            $path = $this->uploadFile('berkas', $validated['attachment']);
            $validated['attachment'] = $path;
        }

        $letter->update([
            ...$validated,
            'status' => LetterStatusEnum::MENUNGGU,
        ]);

        if (
            $request->recipients
            && $user->canAny(
                [
                    PermissionEnum::CREATE_LETTER_FOR_RELAWAN,
                    PermissionEnum::CREATE_LETTER_FOR_PENGURUS
                ]
            )
        ) {
            $request->validate([
                'recipients' => ['required', 'array', 'max:10'],
                'recipients.*' => [
                    $user->hasRole(RoleEnum::ADMIN)
                        ? new ValidPengurusRelawanRule()
                        : new ValidRelawanRule()
                ]
            ]);

            $letter->recipients()->sync($request->recipients);
        }

        flash()->success("Berhasil. Ajuan Surat [{$validated['title']}] telah diperbarui. Admin akan segera meninjau dan memprosesnya");

        return to_route('surat.show', $letter->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Letter $letter): RedirectResponse
    {
        Gate::authorize('destroy', $letter);

        $letterTitle = $letter->title;
        $letter->delete();

        if ($q = parse_url(url()->previous(), PHP_URL_QUERY)) {
            return to_route('surat.index', $q);
        }

        flash()->success("Berhasil. Ajuan Surat [{$letterTitle}] telah dihapus.");

        return to_route('surat.index');
    }

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
