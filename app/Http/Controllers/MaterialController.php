<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Spatie\QueryBuilder\QueryBuilder;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Material::class);

        $materials = QueryBuilder::for(Material::class)
            ->allowedFilters([
                'title',
            ])
            ->latest('updated_at')
            ->paginate(15)
            ->appends(request()->query());

        return view('hris.materi.index', compact('materials'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        Gate::authorize('create', Material::class);

        return view('hris.materi.admin.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create', Material::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url',
        ]);

        $material = Material::create($validated);

        flash()->success("Berhasil. Material [{$material->title}] telah ditambahkan.");

        return to_route('materi.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Material $material): RedirectResponse
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Material $material): View
    {
        Gate::authorize('update', $material);

        return view('hris.materi.admin.edit', compact('material'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Material $material): RedirectResponse
    {
        Gate::authorize('update', $material);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url',
        ]);

        $material->update($validated);

        flash()->success("Berhasil. Material [{$material->title}] telah diperbarui.");

        return to_route('materi.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Material $material): RedirectResponse
    {
        Gate::authorize('delete', $material);

        $material->delete();

        flash()->success("Berhasil. Material [{$material->title}] telah dihapus.");

        if ($q = parse_url(url()->previous(), PHP_URL_QUERY)) {
            return to_route('materi.index')->withQueryString($q);
        }

        return to_route('materi.index');
    }
}
