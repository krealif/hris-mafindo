<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\View\View;
use App\Enums\EventTypeEnum;
use App\Traits\HasUploadFile;
use App\Enums\EventStatusEnum;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\StoreEventRequest;
use Spatie\SimpleExcel\SimpleExcelWriter;

class EventController extends Controller
{
    use HasUploadFile;

    /**
     * Display a listing of all available events.
     */
    public function index(): View
    {
        Gate::authorize('viewAny', Event::class);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $eventsQuery = Event::withCount(['participants']);

        if ($user->hasPermissionTo('join-event')) {
            $eventsQuery->withCount([
                'participants as has_joined' => function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                },
            ]);
        }

        $events = $eventsQuery
            ->where('status', EventStatusEnum::AKTIF)
            ->oldest('start_date')
            ->latest('created_at')
            ->paginate(12);

        return view('hris.kegiatan.index', compact('events'));
    }

    /**
     * Display a listing of events joined by the user.
     */
    public function indexJoined(): View
    {
        Gate::authorize('join-event');

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $events = Event::whereHas('participants', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->oldest('start_date')->paginate(12);

        return view('hris.kegiatan.user.indexJoined', compact('events'));
    }

    /**
     * Display a listing of all available events.
     */
    public function indexHistory(): View
    {
        Gate::authorize('viewAny', Event::class);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $eventsQuery = Event::withCount(['participants']);

        if ($user->hasPermissionTo('join-event')) {
            $eventsQuery->withCount([
                'participants as has_joined' => function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                },
            ]);
        }

        $events = $eventsQuery
            ->where('status', EventStatusEnum::AKTIF)
            ->oldest('start_date')
            ->latest('created_at')
            ->paginate(12);

        return view('hris.kegiatan.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        Gate::authorize('create', Event::class);

        return view('hris.kegiatan.admin.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEventRequest $request): RedirectResponse
    {
        Gate::authorize('create', Event::class);

        $validated = $request->validated();

        if ($request->hasFile('cover')) {
            $path = $this->uploadFile('kegiatan', $validated['cover'], 'public');
            $validated['cover'] = $path;
        }

        Event::create([
            ...$validated,
            'status' => EventStatusEnum::AKTIF,
        ]);

        return to_route('kegiatan.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event): View
    {
        Gate::authorize('view', $event);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $withCounts = [
            'participants'
        ];

        if ($user->hasPermissionTo('join-event')) {
            $withCounts['participants as has_joined'] = function ($query) use ($user) {
                $query->where('user_id', $user->id);
            };

            $withCounts['certificates as has_certificate'] = function ($query) use ($user) {
                $query->where('user_id', $user->id);
            };
        }

        $event->loadCount($withCounts);

        return view('hris.kegiatan.detail', compact('event'));
    }

    /**
     * Display the specified resource.
     */
    public function showParticipant(Event $event): View
    {
        Gate::authorize('view-participant', $event);

        $participants = $event->participants()
            ->latest('created_at')
            ->paginate(15);

        return view('hris.kegiatan.admin.list-participant', compact('event', 'participants'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event): View
    {
        Gate::authorize('update', $event);

        return view('hris.kegiatan.admin.edit', compact('event'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreEventRequest $request, Event $event): RedirectResponse
    {
        Gate::authorize('update', $event);

        $validated = $request->validated();

        if ($request->hasFile('cover')) {
            $path = $this->uploadFile('kegiatan', $validated['cover'], 'public');
            $validated['cover'] = $path;
        }

        // Memastikan quota bernilai null saat mengubah tipe terbatas menjadi terbuka
        if (
            $request->input('type') == EventTypeEnum::TERBUKA->value
            && $event->status == EventStatusEnum::AKTIF
        ) {
            $validated['quota'] = null;
        }

        $event->update([
            ...$validated,
        ]);

        return to_route('kegiatan.show', $event->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event): RedirectResponse
    {
        Gate::authorize('delete', $event);
        // TODO
        $event->delete();

        return to_route('kegiatan.index');
    }

    public function join(Event $event): RedirectResponse
    {
        Gate::authorize('join', $event);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Muat data peserta dan status keikutsertaan
        $event->loadCount([
            'participants' => function ($query) {
                $query->where('type', EventTypeEnum::TERBATAS);
            },
            'participants as has_joined' => function ($query) use ($user) {
                $query->where('user_id', $user->id);
            },
        ]);

        if (!$event->has_joined) {
            if ($event->type == EventTypeEnum::TERBATAS && $event->participants_count >= $event->quota) {
                abort(403);
            }
            $event->participants()->attach($user);
        } else {
            $event->participants()->detach($user);
        }

        return to_route('kegiatan.show', $event->id);
    }

    public function finish(Event $event): RedirectResponse
    {
        Gate::authorize('finish', $event);

        $event->update([
            'status' => EventStatusEnum::SELESAI,
        ]);

        return to_route('kegiatan.show', $event->id);
    }

    public function exportParticipant(Event $event): void
    {
        Gate::authorize('view-participant', $event);

        $participants = $event->participants()
            ->with('branch')
            ->latest('created_at')
            ->lazy();

        $filename = "peserta-kegiatan-{$event->id}-{$event->name}.csv";

        $writer = SimpleExcelWriter::streamDownload($filename);

        foreach ($participants as $participant) {
            $writer->addRow([
                'Nama' => $participant->nama,
                'Email' => $participant->email,
                'No. Relawan' => $participant->no_relawan,
                'Wilayah' => $participant->branch?->name,
                'Tanggal' => $participant->pivot->created_at?->format('Y-m-d H:i'),
            ]);
        }

        $writer->toBrowser();
    }
}
