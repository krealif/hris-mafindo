<?php

namespace App\Http\Controllers;

use App\Enums\LetterStatusEnum;
use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use App\Models\Letter;
use App\Models\LetterTemplate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class LetterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $relations = [
            'template',
            'submittedBy.roles',
        ];

        if ($user->can(PermissionEnum::CREATE_LETTER_FOR_RELAWAN)) {
            $relations[] = 'submittedFor';
        }

        $letters = Letter::whereBelongsTo($user, 'submittedBy')
            ->orWhere(function ($query) use ($user) {
                $query->where('submitted_for_id', $user->id)
                    ->where('status', LetterStatusEnum::SELESAI);
            })
            // ->orWhere('submitted_for_id', $user->id)
            ->with($relations)
            ->latest('updated_at')
            ->paginate(15);

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

        $letters = Letter::where('submitted_by_id', '!=', $user->id)
            ->where(function ($query) use ($user) {
                $query->where('submitted_for_id', '!=', $user->id)
                    ->orWhereNull('submitted_for_id');
            })
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('users')
                    ->whereRaw('letters.submitted_for_id = users.id OR letters.submitted_by_id = users.id')
                    ->where('users.branch_id', 1);
            })
            ->with('template', 'submittedBy', 'submittedFor')
            ->latest('updated_at')
            ->paginate(15);

        return view('hris.surat.user.index-wilayah', compact('letters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(LetterTemplate $template): View
    {
        Gate::authorize('create', Letter::class);

        return view('hris.surat.user.create', [
            'template' => $template,
            'letter' => null,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, LetterTemplate $template): RedirectResponse
    {
        Gate::authorize('create', Letter::class);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $content = $request->except(['_token', 'submitted_for_id']);
        $data = [
            'template_id' => $template->id,
            'submitted_by_id' => Auth::id(),
            'content' => $content,
            'status' => $user->hasRole(RoleEnum::ADMIN)
                ? LetterStatusEnum::DIPROSES
                : LetterStatusEnum::MENUNGGU,
        ];

        if ($user->can(PermissionEnum::CREATE_LETTER_FOR_RELAWAN)) {
            $rule = ['submitted_for_id' => ['required', 'exists:users,id']];

            // TODO: VERIFIKASI ROLE USER

            $validated = $request->validate($rule);
            $data['submitted_for_id'] = $validated['submitted_for_id'];
        }

        Letter::create($data);

        return to_route('surat.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Letter $letter): View
    {
        Gate::authorize('view', $letter);

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
    public function update(Request $request, Letter $letter): RedirectResponse
    {
        Gate::authorize('update', $letter);

        $letter->update([
            'content' => $request->except('_token'),
        ]);

        return to_route('surat.show', $letter->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Letter $letter)
    {
        Gate::authorize('destroy', $letter);

        $letter->delete();

        if ($q = parse_url(url()->previous(), PHP_URL_QUERY)) {
            return to_route('surat.index', $q);
        }

        return to_route('surat.index');
    }

    public function download(Letter $letter): RedirectResponse
    {
        Gate::authorize('download', $letter);

        if (! $letter->file) {
            return abort(404);
        }

        $url = Storage::temporaryUrl(
            $letter->file,
            now()->addMinutes(60)
        );

        return redirect($url);
    }
}
