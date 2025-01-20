<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Materi;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Enums\RoleEnum;

class MateriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        $no = 1;
        $query = Materi::query();

        if ($request->has('search') && !empty($request->query('search'))) {
            $search = $request->query('search');
            $query->where('title', 'like', '%' . $search . '%');
        }

        switch ($request->query('sort')) {
            case 'newest':
                $query->orderBy('created_at', 'desc'); 
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc'); 
                break;
            case 'alphabet':
                $query->orderBy('title', 'asc'); 
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }
        
        $materis = $query->paginate(15);

        if ($user->hasRole(RoleEnum::ADMIN)) {
            return view('hris.materi.admin.index', compact('materis', 'no'));
        } else {
            return view('hris.materi.user.index', compact('materis', 'no'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('hris.materi.admin.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url',
        ]);
    
        $materi = Materi::create($validatedData);

        flash()->success("Materi {$materi->title} berhasil dibuat!");

        return to_route('materi.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        $materi = Materi::find($id);

        return view('hris.materi.admin.edit', compact('materi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Materi $materi)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url',
        ]);

        $materi->update([
            'title' => $validatedData['title'],
            'url' => $validatedData['url'],
        ]);

        flash()->success("Materi {$materi->title} berhasil diubah!");

        return to_route('materi.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Materi $materi)
    {
        $materi->delete();
        if ($q = parse_url(url()->previous(), PHP_URL_QUERY)) {
            return to_route('materi.index')->withQueryString($q); 
        }
        flash()->success("Materi {$materi->title} berhasil dihapus!");

        return to_route('materi.index'); 
    }
}
