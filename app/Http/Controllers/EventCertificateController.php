<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use Illuminate\View\View;
use App\Traits\HasUploadFile;
use App\Models\EventCertificate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\RedirectResponse;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreEventCertificateRequest;
use App\Notifications\CertificateReceived;

class EventCertificateController extends Controller
{
    use HasUploadFile;

    /**
     * Display a listing of the resource.
     */
    public function index(Event $event): View
    {
        Gate::authorize('manageCertificate', $event);

        $event->loadCount(['participants']);

        $userCertificates = QueryBuilder::for($event->certificates())
            ->allowedFilters(['nama'])
            ->paginate(15)
            ->appends(request()->query());

        return view('hris.kegiatan-sertifikat.index', compact('event', 'userCertificates'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Event $event): View
    {
        Gate::authorize('manageCertificate', $event);

        $event->loadCount(['participants']);

        return view('hris.kegiatan-sertifikat.create', compact('event'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEventCertificateRequest $request, Event $event): RedirectResponse
    {
        Gate::authorize('manageCertificate', $event);

        $validated = $request->validated();

        if ($request->hasFile('file')) {
            $path = $this->uploadFile('sertifikat', $validated['file'], 'public');
            $validated['file'] = $path;
        }

        $event->certificates()->attach(
            $validated['relawan_id'],
            [
                'file' => $validated['file']
            ]
        );

        // Send email
        /** @var \App\Models\User|null $userCertificate */
        $userCertificate = User::find($validated['relawan_id']);

        if ($userCertificate) {
            $userCertificate->notify(new CertificateReceived(
                $userCertificate,
                $event,
                $validated['file']
            ));
        }

        flash()->success("Berhasil. Sertifikat telah ditambahkan");
        return to_route('sertifikat.index', $event->id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event, EventCertificate $certificate): View
    {
        Gate::authorize('manageCertificate', $event);

        return view('hris.kegiatan-sertifikat.edit', compact('event', 'certificate'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        StoreEventCertificateRequest $request,
        Event $event,
        EventCertificate $certificate
    ): RedirectResponse {
        Gate::authorize('manageCertificate', $event);

        $validated = $request->validated();

        if ($request->hasFile('file')) {
            $path = $this->uploadFile('sertifikat', $validated['file'], 'public');
            $validated['file'] = $path;
        }

        $certificate->update([
            'file' => $validated['file']
        ]);

        flash()->success("Berhasil. Sertifikat telah diperbarui");
        return to_route('sertifikat.index', $event->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event, EventCertificate $certificate): RedirectResponse
    {
        Gate::authorize('manageCertificate', $event);

        $certificate->delete();

        flash()->success("Berhasil. Sertifikat telah dihapus.");
        return to_route('sertifikat.index', $event->id);
    }

    /**
     * Download the certificate associated with the Relawan for the given event.
     */
    public function downloadForRelawan(Event $event): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $certificate = EventCertificate::select('file')
            ->where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $url = Storage::url($certificate->file);

        return redirect($url);
    }
}
