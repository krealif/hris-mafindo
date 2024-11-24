<?php

namespace App\Http\Controllers;

use App\Enums\LetterStatusEnum;
use App\Models\Letter;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Traits\HasUploadFile;
use App\Models\LetterTemplate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Stmt\Return_;

class LetterController extends Controller
{
    use HasUploadFile;
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        if (Gate::allows('viewAny', Letter::class)) {
            $letters = Letter::with('user', 'letterTemplate')
                ->get();
        } else {
            $letters = Letter::where('user_id', Auth::id())
                ->with('letterTemplate')
                ->get();
        }

        return view('hris.letter.index', compact('letters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(LetterTemplate $letterTemplate): View
    {
        Gate::authorize('create', Letter::class);

        return view('hris.letter.create', [
            'template' => $letterTemplate,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, LetterTemplate $letterTemplate): RedirectResponse
    {
        Gate::authorize('create', Letter::class);

        $contents = $request->except('_token');
        Letter::create([
            'user_id' => Auth::id(),
            'letter_template_id' => $letterTemplate->id,
            'contents' => $contents,
        ]);

        return to_route('letter.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Letter $letter)
    {
        Gate::authorize('view', $letter);

        return view('hris.letter.detail', compact('letter'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Letter $letter): View
    {
        Gate::authorize('update', $letter);

        return view('hris.letter.edit', compact('letter'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Letter $letter): RedirectResponse
    {
        Gate::authorize('update', $letter);

        $contents = $request->except('_token');
        $letter->update([
            'contents' => $contents,
        ]);

        return to_route('letter.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Letter $letter)
    {
        //
    }

    /**
     * Review submitted letter by user.
     */
    public function review(Letter $letter): View
    {
        Gate::authorize('review', $letter);

        // Change letter status
        if ($letter->status == LetterStatusEnum::MENUNGGU->value) {
            $letter->update(['status' => LetterStatusEnum::DIPROSES->value]);
        }

        return view('hris.letter.review', compact('letter'));
    }

    /**
     * Review submitted letter by user.
     */
    public function upload(Request $request, Letter $letter): RedirectResponse
    {
        Gate::authorize('review', $letter);

        $validated = $request->validate([
            'admin' => ['required', 'string', 'max:255'],
            'letter' => ['required', 'mimes:pdf,doc,docx', 'max:5120'],
        ]);

        if ($request->hasFile('letter')) {
            $uploadedPath = $this->uploadFile('surat', $validated['letter']);
        }

        // Remove old file
        if ($oldFile = $letter->file) {
            $this->deleteFile($oldFile);
        }

        $letter->update([
            'file' => $uploadedPath,
            'admin' => $validated['admin'],
        ]);

        flash()->success('Berhasil! File surat telah diupload');
        return to_route('letter.review', $letter->id);
    }

    public function reject(Request $request, Letter $letter)
    {
        $validated = $request->validate([
            'admin' => ['required', 'string', 'max:255'],
            'message' => ['required', 'mimes:pdf,doc,docx', 'max:5120'],
        ]);
    }

    public function download(Letter $letter): RedirectResponse
    {
        $url = Storage::temporaryUrl(
            $letter->file, now()->addMinutes(60)
        );

        return redirect($url);
    }
}
