<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Category;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ItemController extends Controller
{

    public function index(Request $request)
    {
        $query = Item::with(['category', 'unit'])
            ->withSum('stocks', 'quantity');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('code', 'like', "%{$request->search}%")
                    ->orWhere('barcode', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('low_stock')) {
            $query->whereHas('stocks', function ($q) {
                $q->whereRaw('quantity <= min_stock');
            });
        }

        $items      = $query->latest()->paginate(15)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('master.items.index', compact('items', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $units      = Unit::orderBy('name')->get();
        $code       = $this->generateCode();

        return view('master.items.create', compact('categories', 'units', 'code'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code'           => 'required|string|max:50|unique:items,code',
            'name'           => 'required|string|max:255',
            'category_id'    => 'required|exists:categories,id',
            'unit_id'        => 'required|exists:units,id',
            'purchase_price' => 'nullable|numeric|min:0',
            'min_stock'      => 'nullable|integer|min:0',
            'description'    => 'nullable|string',
            'image'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active'      => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        // Generate barcode string dari kode barang
        $validated['barcode'] = $validated['code'];

        // Upload gambar
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('items', 'public');
        }

        Item::create($validated);

        return redirect()->route('master.items.index')
            ->with('success', 'Barang berhasil ditambahkan.');
    }

    public function show(Item $item)
    {
        $item->load(['category', 'unit', 'stocks.warehouse']);
        return view('master.items.show', compact('item'));
    }

    public function edit(Item $item)
    {
        $categories = Category::orderBy('name')->get();
        $units      = Unit::orderBy('name')->get();

        return view('master.items.edit', compact('item', 'categories', 'units'));
    }

    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'code'           => 'required|string|max:50|unique:items,code,' . $item->id,
            'name'           => 'required|string|max:255',
            'category_id'    => 'required|exists:categories,id',
            'unit_id'        => 'required|exists:units,id',
            'purchase_price' => 'nullable|numeric|min:0',
            'min_stock'      => 'nullable|integer|min:0',
            'description'    => 'nullable|string',
            'image'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active'      => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['barcode']   = $validated['code'];

        if ($request->hasFile('image')) {
            // Hapus gambar lama
            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }
            $validated['image'] = $request->file('image')->store('items', 'public');
        }

        $item->update($validated);

        return redirect()->route('master.items.index')
            ->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy(Item $item)
    {
        if ($item->stocks()->where('quantity', '>', 0)->exists()) {
            return back()->with('error', 'Barang tidak dapat dihapus karena masih memiliki stok.');
        }

        if ($item->image) {
            Storage::disk('public')->delete($item->image);
        }

        $item->delete();

        return redirect()->route('master.items.index')
            ->with('success', 'Barang berhasil dihapus.');
    }

    // Generate barcode image (Code128)
    public function barcode(Item $item)
    {
        $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
        $barcode   = base64_encode(
            $generator->getBarcode($item->barcode ?? $item->code, $generator::TYPE_CODE_128)
        );

        return view('master.items.barcode', compact('item', 'barcode'));
    }

    // Generate QR code
    // public function qrcode(Item $item)
    // {
    //     $qrcode = base64_encode(
    //         QrCode::format('png')
    //             ->size(300)
    //             ->errorCorrection('H')
    //             ->generate(json_encode([
    //                 'code' => $item->code,
    //                 'name' => $item->name,
    //                 'unit' => $item->unit->abbreviation ?? '',
    //             ]))
    //     );

    //     return view('master.items.qrcode', compact('item', 'qrcode'));
    // }
    public function qrcode(Item $item)
    {
        $qrcode = QrCode::format('svg')
            ->size(300)
            ->errorCorrection('H')
            ->generate($item->code);

        return response($qrcode)->header('Content-Type', 'image/svg+xml');
    }

    private function generateCode(): string
    {
        $last = Item::withTrashed()->latest('id')->value('code');

        if (!$last) {
            return 'BRG-0001';
        }

        $num = (int) substr($last, -4) + 1;
        return 'BRG-' . str_pad($num, 4, '0', STR_PAD_LEFT);
    }
}
