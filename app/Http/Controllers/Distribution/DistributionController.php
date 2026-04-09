<?php

namespace App\Http\Controllers\Distribution;

use App\Http\Controllers\Controller;
use App\Models\Distribution;
use App\Models\Item;
use App\Models\Warehouse;
use App\Services\DistributionService;
use Illuminate\Http\Request;

class DistributionController extends Controller
{
    public function __construct(protected DistributionService $distributionService) {}

    public function index(Request $request)
    {
        $query = Distribution::with(['warehouse', 'issuer'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->warehouse_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('dist_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('dist_date', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('dist_number', 'like', "%{$request->search}%")
                  ->orWhere('destination', 'like', "%{$request->search}%")
                  ->orWhere('recipient', 'like', "%{$request->search}%");
            });
        }

        $distributions = $query->paginate(15)->withQueryString();
        $warehouses    = Warehouse::where('is_active', true)->orderBy('name')->get();

        return view('distribution.index', compact('distributions', 'warehouses'));
    }

    public function create()
    {
        $warehouses = Warehouse::where('is_active', true)->orderBy('name')->get();
        $items      = Item::where('is_active', true)->with(['unit', 'stocks'])->orderBy('name')->get();

        return view('distribution.create', compact('warehouses', 'items'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'warehouse_id'        => 'required|exists:warehouses,id',
            'destination'         => 'required|string|max:255',
            'recipient'           => 'nullable|string|max:255',
            'dist_date'           => 'required|date',
            'notes'               => 'nullable|string',
            'items'               => 'required|array|min:1',
            'items.*.item_id'     => 'required|exists:items,id',
            'items.*.quantity'    => 'required|integer|min:1',
        ]);

        try {
            $distribution = $this->distributionService->create($validated);

            return redirect()->route('distribution.show', $distribution)
                ->with('success', "Distribusi {$distribution->dist_number} berhasil dibuat.");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function show(Distribution $distribution)
    {
        $distribution->load(['warehouse', 'issuer', 'items.item.unit']);

        return view('distribution.show', compact('distribution'));
    }

    public function issue(Distribution $distribution)
    {
        try {
            $this->distributionService->issue($distribution);

            return back()->with('success', 'Distribusi berhasil diterbitkan. Stok telah dikurangi.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function cancel(Distribution $distribution)
    {
        try {
            $this->distributionService->cancel($distribution);

            return back()->with('success', 'Distribusi berhasil dibatalkan.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function print(Distribution $distribution)
    {
        $distribution->load(['warehouse', 'issuer', 'items.item.unit']);

        return view('distribution.print', compact('distribution'));
    }
}