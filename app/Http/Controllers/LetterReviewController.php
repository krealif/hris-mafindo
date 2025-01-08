<?php

namespace App\Http\Controllers;

use App\Enums\LetterStatusEnum;
use App\Filters\FilterDate;
use App\Filters\FilterLetterType;
use App\Filters\FilterRecipientLetter;
use App\Models\Letter;
use App\Traits\HasUploadFile;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rules\File;
use Illuminate\View\View;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class LetterReviewController extends Controller
{
    use HasUploadFile;

    /**
     * Display a list of letter application that are either waiting or being processed.
     */
    public function indexSubmission(): View
    {
        Gate::authorize('viewAny', Letter::class);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $letters = QueryBuilder::for(Letter::class)
            ->allowedFilters([
                'title',
                'created_by',
                AllowedFilter::custom('recipient', new FilterRecipientLetter),
                AllowedFilter::custom('updated_at', new FilterDate),
            ])
            ->where(function ($query) {
                $query->whereIn('status', [
                    LetterStatusEnum::MENUNGGU,
                    LetterStatusEnum::DIPROSES,
                ]);
            })
            ->with('createdBy.roles', 'recipients')
            ->latest('updated_at')
            ->paginate(15);

        return view('hris.surat.admin.index', compact('letters'));
    }

    /**
     * Display a list of letter application history.
     */
    public function indexHistory(): View
    {
        Gate::authorize('viewAny', Letter::class);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $letters = QueryBuilder::for(Letter::class)
            ->allowedFilters([
                'title',
                'created_by',
                'status',
                AllowedFilter::custom('type', new FilterLetterType),
                AllowedFilter::custom('recipient', new FilterRecipientLetter),
                AllowedFilter::custom('updated_at', new FilterDate),
            ])
            ->whereNotIn('status', [LetterStatusEnum::MENUNGGU, LetterStatusEnum::DIPROSES])
            ->with('createdBy.roles', 'recipients')
            ->latest('updated_at')
            ->paginate(15);

        return view('hris.surat.admin.index-history', compact('letters'));
    }

    /**
     * Upload the result file for a letter application.
     */
    public function uploadResult(Request $request, Letter $letter): RedirectResponse
    {
        Gate::authorize('handleSubmission', $letter);

        $validated = $request->validate([
            'admin' => ['required', 'string', 'max:255'],
            'file' => [
                'required',
                File::types(['pdf', 'doc', 'docx'])
                    ->min('1kb')
                    ->max('2mb'),
            ],
        ]);

        if ($request->hasFile('file')) {
            $path = $this->uploadFile('surat', $validated['file']);
        }

        $letter->update([
            'result_file' => $path ?? null,
            'uploaded_by' => $validated['admin'],
            'uploaded_at' => \Carbon\Carbon::now(),
        ]);

        flash()->success("Berhasil. Permohonan Surat [{$letter->title}] telah dibuat.");

        return to_route('surat.show', $letter->id);
    }

    /**
     * Request a revision for a letter application.
     */
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

        // TODO: Kirim email

        flash()->success("Berhasil. Permintaan revisi [{$letter->title}] telah dikirimkan kepada yang bersangkutan.");

        return to_route('surat.show', $letter->id);
    }

    /**
     * Approve a letter application and mark it as completed.
     */
    public function approveSubmission(Letter $letter): RedirectResponse
    {
        Gate::authorize('handleSubmission', $letter);

        $letter->update([
            'status' => LetterStatusEnum::SELESAI,
        ]);

        // TODO: Kirim email

        flash()->success("Berhasil. Surat [{$letter->title}] telah dikirim kepada yang bersangkutan.");

        return to_route('surat.show', $letter->id);
    }

    /**
     * Reject a letter application and provide a rejection message.
     */
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

        // TODO: Kirim email

        flash()->success("Berhasil. Permohonan Surat [{$letter->title}] telah ditolak.");

        return to_route('surat.show', $letter->id);
    }

    /**
     * Bulk delete old letter application based on provided criteria.
     */
    public function bulkDelete(Request $request): RedirectResponse
    {
        $criteria = [
            'status_revisi' => [LetterStatusEnum::REVISI, $request->input('lama_revisi')],
            'status_ditolak' => [LetterStatusEnum::DITOLAK, $request->input('lama_ditolak')],
            'status_selesai' => [LetterStatusEnum::SELESAI, $request->input('lama_selesai')],
        ];

        $total = 0;

        foreach ($criteria as $key => [$status, $months]) {
            if ($request->input($key) && $months) {
                $letters = Letter::where('status', $status)
                    ->where('updated_at', '<', Carbon::now()->subMonths($months))
                    ->get();

                // Menghapus data satu per satu untuk men-trigger observer model
                $letters->each(function ($letter) {
                    $letter->delete();
                });

                $total += $letters->count();
            }
        }

        if ($total > 0) {
            flash()->success("Berhasil. Sebanyak [{$total}] permohonan surat telah dihapus.");
        } else {
            flash()->info('Tidak ada data yang perlu dihapus.');
        }

        return to_route('surat.indexHistory');
    }
}
