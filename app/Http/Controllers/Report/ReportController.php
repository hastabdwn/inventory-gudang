<?php

namespace App\Http\Controllers\Report;

use App\Exports\DistributionReportExport;
use App\Exports\MovementReportExport;
use App\Exports\PurchaseOrderReportExport;
use App\Exports\ReturnReportExport;
use App\Exports\StockReportExport;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Item;
use App\Models\Supplier;
use App\Models\Warehouse;
use App\Services\ReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function __construct(protected ReportService $reportService) {}

    public function index()
    {
        $summary = $this->reportService->stockSummary();
        return view('reports.index', compact('summary'));
    }

    // ── Laporan Stok ───────────────────────────────────────────
    public function stock(Request $request)
    {
        $filters    = $request->only(['warehouse_id', 'category_id', 'low_stock', 'search']);
        $data       = $this->reportService->stockReport($filters);
        $warehouses = Warehouse::where('is_active', true)->orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('reports.stock', compact('data', 'warehouses', 'categories', 'filters'));
    }

    public function exportStock(Request $request, string $format)
    {
        $filters  = $request->only(['warehouse_id', 'category_id', 'low_stock', 'search']);
        $data     = $this->reportService->stockReport($filters);
        $filename = 'laporan-stok-' . now()->format('Ymd-His');

        if ($format === 'excel') {
            return Excel::download(new StockReportExport($data), $filename . '.xlsx');
        }

        $pdf = Pdf::loadView('reports.pdf.stock', compact('data', 'filters'))
            ->setPaper('a4', 'landscape');
        return $pdf->download($filename . '.pdf');
    }

    // ── Laporan Mutasi ─────────────────────────────────────────
    public function movements(Request $request)
    {
        $filters    = $request->only(['item_id', 'warehouse_id', 'type', 'date_from', 'date_to']);
        $data       = $this->reportService->movementReport($filters);
        $items      = Item::where('is_active', true)->orderBy('name')->get();
        $warehouses = Warehouse::where('is_active', true)->orderBy('name')->get();

        $types = [
            'in'           => 'Masuk',
            'out'          => 'Keluar',
            'transfer_in'  => 'Transfer Masuk',
            'transfer_out' => 'Transfer Keluar',
            'retur'        => 'Retur',
            'adjustment'   => 'Penyesuaian',
        ];

        return view('reports.movements', compact('data', 'items', 'warehouses', 'types', 'filters'));
    }

    public function exportMovements(Request $request, string $format)
    {
        $filters  = $request->only(['item_id', 'warehouse_id', 'type', 'date_from', 'date_to']);
        $data     = $this->reportService->movementReport($filters);
        $filename = 'laporan-mutasi-' . now()->format('Ymd-His');

        if ($format === 'excel') {
            return Excel::download(new MovementReportExport($data), $filename . '.xlsx');
        }

        $pdf = Pdf::loadView('reports.pdf.movements', compact('data', 'filters'))
            ->setPaper('a4', 'landscape');
        return $pdf->download($filename . '.pdf');
    }

    // ── Laporan PO ─────────────────────────────────────────────
    public function purchaseOrders(Request $request)
    {
        $filters    = $request->only(['supplier_id', 'warehouse_id', 'status', 'date_from', 'date_to']);
        $data       = $this->reportService->purchaseOrderReport($filters);
        $suppliers  = Supplier::where('is_active', true)->orderBy('name')->get();
        $warehouses = Warehouse::where('is_active', true)->orderBy('name')->get();

        $statuses = [
            'draft'            => 'Draft',
            'waiting_approval' => 'Menunggu Approval',
            'approved'         => 'Disetujui',
            'partial'          => 'Diterima Sebagian',
            'completed'        => 'Selesai',
            'cancelled'        => 'Dibatalkan',
        ];

        return view('reports.purchase-orders', compact('data', 'suppliers', 'warehouses', 'statuses', 'filters'));
    }

    public function exportPurchaseOrders(Request $request, string $format)
    {
        $filters  = $request->only(['supplier_id', 'warehouse_id', 'status', 'date_from', 'date_to']);
        $data     = $this->reportService->purchaseOrderReport($filters);
        $filename = 'laporan-po-' . now()->format('Ymd-His');

        if ($format === 'excel') {
            return Excel::download(new PurchaseOrderReportExport($data), $filename . '.xlsx');
        }

        $pdf = Pdf::loadView('reports.pdf.purchase-orders', compact('data', 'filters'))
            ->setPaper('a4', 'landscape');
        return $pdf->download($filename . '.pdf');
    }

    // ── Laporan Distribusi ─────────────────────────────────────
    public function distributions(Request $request)
    {
        $filters    = $request->only(['warehouse_id', 'status', 'date_from', 'date_to', 'search']);
        $data       = $this->reportService->distributionReport($filters);
        $warehouses = Warehouse::where('is_active', true)->orderBy('name')->get();

        return view('reports.distributions', compact('data', 'warehouses', 'filters'));
    }

    public function exportDistributions(Request $request, string $format)
    {
        $filters  = $request->only(['warehouse_id', 'status', 'date_from', 'date_to', 'search']);
        $data     = $this->reportService->distributionReport($filters);
        $filename = 'laporan-distribusi-' . now()->format('Ymd-His');

        if ($format === 'excel') {
            return Excel::download(new DistributionReportExport($data), $filename . '.xlsx');
        }

        $pdf = Pdf::loadView('reports.pdf.distributions', compact('data', 'filters'))
            ->setPaper('a4', 'landscape');
        return $pdf->download($filename . '.pdf');
    }

    // ── Laporan Retur ──────────────────────────────────────────
    public function returns(Request $request)
    {
        $filters    = $request->only(['supplier_id', 'warehouse_id', 'status', 'date_from', 'date_to']);
        $data       = $this->reportService->returnReport($filters);
        $suppliers  = Supplier::where('is_active', true)->orderBy('name')->get();
        $warehouses = Warehouse::where('is_active', true)->orderBy('name')->get();

        return view('reports.returns', compact('data', 'suppliers', 'warehouses', 'filters'));
    }

    public function exportReturns(Request $request, string $format)
    {
        $filters  = $request->only(['supplier_id', 'warehouse_id', 'status', 'date_from', 'date_to']);
        $data     = $this->reportService->returnReport($filters);
        $filename = 'laporan-retur-' . now()->format('Ymd-His');

        if ($format === 'excel') {
            return Excel::download(new ReturnReportExport($data), $filename . '.xlsx');
        }

        $pdf = Pdf::loadView('reports.pdf.returns', compact('data', 'filters'))
            ->setPaper('a4', 'landscape');
        return $pdf->download($filename . '.pdf');
    }
}