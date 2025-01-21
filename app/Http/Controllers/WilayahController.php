<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Enums\RoleEnum;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class WilayahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $no = 1;
        $cities = QueryBuilder::for(Branch::class)
            ->allowedFilters([
                'name',
            ])
            ->orderBy('name', 'asc')
            ->paginate(15);

        return view('hris.wilayah.index', compact('cities', 'no'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);
    
        $city = Branch::create($validatedData);

        flash()->success("Wilayah {$city->name} berhasil ditambahkan!");

        return to_route('wilayah.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $city = Branch::find($id);
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $city->update([
            'name' => $validatedData['name'],
        ]);

        flash()->success("Wilayah {$city->name} berhasil diubah!");

        return to_route('wilayah.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $city = Branch::find($id);
        $city->delete();
        if ($q = parse_url(url()->previous(), PHP_URL_QUERY)) {
            return to_route('wilayah.index')->withQueryString($q); 
        }
        
        flash()->success("Wilayah {$city->name} berhasil dihapus!");

        return to_route('wilayah.index'); 
    }
}
