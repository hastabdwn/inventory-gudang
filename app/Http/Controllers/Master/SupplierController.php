<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplier::withCount('purchaseOrders');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('code', 'like', "%{$request->search}%");
            });
        }

        $suppliers = $query->latest()->paginate(10)->withQueryString();

        return view('master.suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('master.suppliers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code'           => 'required|string|max:20|unique:suppliers,code',
            'name'           => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone'          => 'nullable|string|max:20',
            'email'          => 'nullable|email|max:255',
            'address'        => 'nullable|string',
            'is_active'      => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        Supplier::create($validated);

        return redirect()->route('master.suppliers.index')
            ->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function show(Supplier $supplier)
    {
        $supplier->load('purchaseOrders');
        return view('master.suppliers.show', compact('supplier'));
    }

    public function edit(Supplier $supplier)
    {
        return view('master.suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'code'           => 'required|string|max:20|unique:suppliers,code,' . $supplier->id,
            'name'           => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone'          => 'nullable|string|max:20',
            'email'          => 'nullable|email|max:255',
            'address'        => 'nullable|string',
            'is_active'      => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $supplier->update($validated);

        return redirect()->route('master.suppliers.index')
            ->with('success', 'Supplier berhasil diperbarui.');
    }

    public function destroy(Supplier $supplier)
    {
        if ($supplier->purchaseOrders()->exists()) {
            return back()->with('error', 'Supplier tidak dapat dihapus karena memiliki riwayat PO.');
        }

        $supplier->delete();

        return redirect()->route('master.suppliers.index')
            ->with('success', 'Supplier berhasil dihapus.');
    }
}
