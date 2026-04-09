<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{

    public function index()
    {
        $units = Unit::withCount('items')->latest()->paginate(10);
        return view('master.units.index', compact('units'));
    }

    public function create()
    {
        return view('master.units.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:50|unique:units,name',
            'abbreviation' => 'required|string|max:20|unique:units,abbreviation',
        ]);

        Unit::create($validated);

        return redirect()->route('master.units.index')
            ->with('success', 'Satuan berhasil ditambahkan.');
    }

    public function show(Unit $unit)
    {
        return view('master.units.show', compact('unit'));
    }

    public function edit(Unit $unit)
    {
        return view('master.units.edit', compact('unit'));
    }

    public function update(Request $request, Unit $unit)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:50|unique:units,name,' . $unit->id,
            'abbreviation' => 'required|string|max:20|unique:units,abbreviation,' . $unit->id,
        ]);

        $unit->update($validated);

        return redirect()->route('master.units.index')
            ->with('success', 'Satuan berhasil diperbarui.');
    }

    public function destroy(Unit $unit)
    {
        if ($unit->items()->exists()) {
            return back()->with('error', 'Satuan tidak dapat dihapus karena masih digunakan.');
        }

        $unit->delete();

        return redirect()->route('master.units.index')
            ->with('success', 'Satuan berhasil dihapus.');
    }
}