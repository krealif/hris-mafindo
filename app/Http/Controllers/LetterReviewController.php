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
            ->with('createdBy.roles', 'recipients')
            ->latest('updated_at')
            ->paginate(15);

        return view('hris.surat.admin.index', compact('letters'));
    }


    public function uploadResult(Request $request, Letter $letter): RedirectResponse
    {
        Gate::authorize('handleSubmission', $letter);

        $validated = $request->validate([
            'admin' => ['required', 'string', 'max:255'],
            'file' => ['required', 'mimes:pdf,doc,docx', 'max:5120'],
        ]);

        if ($request->hasFile('file')) {
            $path = $this->uploadFile('surat', $validated['file']);
        }

        $letter->update([
            'result_file' => $path ?? null,
            'uploaded_by' => $validated['admin'],
            'uploaded_at' => \Carbon\Carbon::now(),
        ]);

        flash()->success("Berhasil. Ajuan Surat [{$letter->title}] telah dibuat.");

        return to_route('surat.show', $letter->id);
    }

    public function requestRevision(Request $request, Letter $letter): RedirectResponse
    {
        Gate::authorize('handleSubmission', $letter);

        $validated = $request->validate([
            'message' => ['required', 'string', 'max:255'],
        ]);

        $letter->update([
            'status' => LetterStatusEnum::REVISI,
            'message' => $validated['message'],
        ]);
        flash()->success("Berhasil. Permintaan revisi [{$letter->title}] telah dikirimkan ke pihak terkait.");

        return to_route('surat.show', $letter->id);
    }

    public function approveSubmission(Letter $letter): RedirectResponse
    {
        Gate::authorize('handleSubmission', $letter);

        $letter->update([
            'status' => LetterStatusEnum::SELESAI,
        ]);

        flash()->success("Berhasil. Surat [{$letter->title}] telah dikirim ke pihak terkait.");

        return to_route('surat.show', $letter->id);
    }

    public function rejectSubmission(Request $request, Letter $letter): RedirectResponse
    {
        Gate::authorize('handleSubmission', $letter);

        $validated = $request->validate([
            'message' => ['required', 'string', 'max:255'],
        ]);

        $letter->update([
            'status' => LetterStatusEnum::DITOLAK,
            'message' => $validated['message'],
        ]);

        flash()->success("Berhasil. Ajuan Surat [{$letter->title}] telah ditolak.");

        return to_route('surat.show', $letter->id);
    }
}
