<?php

namespace App\Http\Controllers;

use App\Enums\LetterStatusEnum;
use App\Models\Letter;
use App\Traits\HasUploadFile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class LetterReviewController extends Controller
{
    use HasUploadFile;

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        Gate::authorize('viewAny', Letter::class);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $letters = Letter::whereIn('status', [
            LetterStatusEnum::MENUNGGU,
            LetterStatusEnum::DIPROSES,
        ])
            ->with('template', 'submittedBy', 'submittedFor')
            ->latest('updated_at')
            ->paginate(15);

        return view('hris.surat.admin.index', compact('letters'));
    }

    public function review(Letter $letter): View
    {
        Gate::authorize('review', $letter);

        if ($letter->status == LetterStatusEnum::MENUNGGU) {
            $letter->update(['status' => LetterStatusEnum::DIPROSES]);
        }

        return view('hris.surat.admin.review', compact('letter'));
    }

    /**
     * Review submitted letter by user.
     */
    public function upload(Request $request, Letter $letter): RedirectResponse
    {
        Gate::authorize('review', $letter);

        $validated = $request->validate([
            'admin' => ['required', 'string', 'max:255'],
            'file' => ['required', 'mimes:pdf,doc,docx', 'max:5120'],
        ]);

        if ($request->hasFile('file')) {
            $uploadedPath = $this->uploadFile('surat', $validated['file']);
        }

        if ($oldFile = $letter->file) {
            $this->deleteFile($oldFile);
        }

        $letter->update([
            'file' => $uploadedPath ?? null,
            'uploaded_by' => $validated['admin'],
            'uploaded_at' => \Carbon\Carbon::now(),
        ]);

        return to_route('surat.rev.review', $letter->id);
    }

    // public function reject(Request $request, Letter $letter)
    // {
    //     $validated = $request->validate([
    //         'admin' => ['required', 'string', 'max:255'],
    //         'message' => ['required', 'mimes:pdf,doc,docx', 'max:5120'],
    //     ]);
    // }

}
