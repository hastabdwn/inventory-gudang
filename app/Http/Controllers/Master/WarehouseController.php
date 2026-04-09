<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{

    public function index()
    {
        $warehouses = Warehouse::latest()->paginate(10);
        return view('master.warehouses.index', compact('warehouses'));
    }

    public function create()
    {
        return view('master.warehouses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code'        => 'required|string|max:20|unique:warehouses,code',
            'name'        => 'required|string|max:255',
            'location'    => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        Warehouse::create($validated);

        return redirect()->route('master.warehouses.index')
            ->with('success', 'Gudang berhasil ditambahkan.');
    }

    public function show(Warehouse $warehouse)
    {
        $warehouse->load('stocks.item');
        return view('master.warehouses.show', compact('warehouse'));
    }

    public function edit(Warehouse $warehouse)
    {
        return view('master.warehouses.edit', compact('warehouse'));
    }

    public function update(Request $request, Warehouse $warehouse)
    {
        $validated = $request->validate([
            'code'        => 'required|string|max:20|unique:warehouses,code,' . $warehouse->id,
            'name'        => 'required|string|max:255',
            'location'    => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $warehouse->update($validated);

        return redirect()->route('master.warehouses.index')
            ->with('success', 'Gudang berhasil diperbarui.');
    }

    public function destroy(Warehouse $warehouse)
    {
        if ($warehouse->stocks()->where('quantity', '>', 0)->exists()) {
            return back()->with('error', 'Gudang tidak dapat dihapus karena masih memiliki stok.');
        }

        $warehouse->delete();

        return redirect()->route('master.warehouses.index')
            ->with('success', 'Gudang berhasil dihapus.');
    }
}