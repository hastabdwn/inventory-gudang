<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Master\WarehouseController;
use App\Http\Controllers\Master\CategoryController;
use App\Http\Controllers\Master\UnitController;
use App\Http\Controllers\Master\SupplierController;
use App\Http\Controllers\Master\ItemController;
use App\Http\Controllers\Stock\StockController;
use App\Http\Controllers\Stock\StockAdjustmentController;
use App\Http\Controllers\Stock\StockMovementController;
use App\Http\Controllers\Stock\StockTransferController;
use App\Http\Controllers\Purchasing\PurchaseOrderController;
use App\Http\Controllers\Purchasing\GoodsReceiptController;
use App\Http\Controllers\Distribution\DistributionController;
use App\Http\Controllers\ItemReturn\ItemReturnController;
use App\Http\Controllers\Report\ReportController;
use App\Http\Controllers\UserManagement\UserController;
use App\Http\Controllers\UserManagement\ProfileController;


Route::get('/', fn() => redirect()->route('dashboard'));

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ─── Master Data ───────────────────────────────────────────
    Route::prefix('master')->name('master.')->middleware('can:view master-data')->group(function () {
        Route::resource('warehouses', WarehouseController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('units', UnitController::class);
        Route::resource('suppliers', SupplierController::class);
        Route::resource('items', ItemController::class);
        Route::get('items/{item}/barcode', [ItemController::class, 'barcode'])->name('items.barcode');
        Route::get('items/{item}/qrcode',  [ItemController::class, 'qrcode'])->name('items.qrcode');
    });

    // ─── Stok ──────────────────────────────────────────────────
    Route::prefix('stock')->name('stock.')->middleware('can:view stock')->group(function () {
        Route::get('/',                    [StockController::class, 'index'])->name('index');
        Route::get('/warehouse/{warehouse}', [StockController::class, 'byWarehouse'])->name('by-warehouse');
        Route::get('/movements',           [StockMovementController::class, 'index'])->name('movements.index');

        Route::get('/transfer',            [StockTransferController::class, 'index'])->name('transfer.index');
        Route::get('/transfer/{stockTransfer}', [StockTransferController::class, 'show'])->name('transfer.show');

        Route::middleware('can:create transfer')->group(function () {
            Route::get('/transfer/create', [StockTransferController::class, 'create'])->name('transfer.create');
            Route::post('/transfer',       [StockTransferController::class, 'store'])->name('transfer.store');
        });

        Route::middleware('can:adjust stock')->group(function () {
            Route::get('/adjustment/create', [StockAdjustmentController::class, 'create'])->name('adjustment.create');
            Route::post('/adjustment',       [StockAdjustmentController::class, 'store'])->name('adjustment.store');
        });
    });

    // ─── Purchasing ────────────────────────────────────────────
    Route::prefix('purchasing')->name('purchasing.')->group(function () {

        // Semua route statis (tanpa parameter) harus di atas route dinamis
        Route::middleware('can:create purchase-order')->group(function () {
            Route::get('orders/create',  [PurchaseOrderController::class, 'create'])->name('orders.create');
            Route::post('orders',        [PurchaseOrderController::class, 'store'])->name('orders.store');
        });

        Route::middleware('can:receive goods')->group(function () {
            Route::get('receipts',                   [GoodsReceiptController::class, 'index'])->name('receipts.index');
            Route::get('receipts/{goodsReceipt}',    [GoodsReceiptController::class, 'show'])->name('receipts.show');
        });

        // Route dinamis di bawah route statis
        Route::middleware('can:view purchase-order')->group(function () {
            Route::get('orders',                     [PurchaseOrderController::class, 'index'])->name('orders.index');
            Route::get('orders/{purchaseOrder}',     [PurchaseOrderController::class, 'show'])->name('orders.show');
        });

        Route::middleware('can:create purchase-order')->group(function () {
            Route::post('orders/{purchaseOrder}/submit', [PurchaseOrderController::class, 'submit'])->name('orders.submit');
            Route::post('orders/{purchaseOrder}/cancel', [PurchaseOrderController::class, 'cancel'])->name('orders.cancel');
        });

        Route::middleware('can:approve purchase-order')->group(function () {
            Route::post('orders/{purchaseOrder}/approve', [PurchaseOrderController::class, 'approve'])->name('orders.approve');
        });

        Route::middleware('can:receive goods')->group(function () {
            Route::get('orders/{purchaseOrder}/receive',  [GoodsReceiptController::class, 'create'])->name('receipts.create');
            Route::post('orders/{purchaseOrder}/receive', [GoodsReceiptController::class, 'store'])->name('receipts.store');
        });
    });

    // ─── Distribusi ────────────────────────────────────────────
    Route::prefix('distribution')->name('distribution.')->group(function () {

        Route::middleware('can:create distribution')->group(function () {
            Route::get('create',                        [DistributionController::class, 'create'])->name('create');
            Route::post('/',                            [DistributionController::class, 'store'])->name('store');
            Route::post('{distribution}/issue',         [DistributionController::class, 'issue'])->name('issue');
            Route::post('{distribution}/cancel',        [DistributionController::class, 'cancel'])->name('cancel');
        });

        Route::middleware('can:view distribution')->group(function () {
            Route::get('/',                             [DistributionController::class, 'index'])->name('index');
            Route::get('{distribution}',                [DistributionController::class, 'show'])->name('show');
            Route::get('{distribution}/print',          [DistributionController::class, 'print'])->name('print');
        });
    });

    // ─── Retur ─────────────────────────────────────────────────
    Route::prefix('returns')->name('returns.')->group(function () {

        Route::middleware('can:create return')->group(function () {
            Route::get('create',                    [ItemReturnController::class, 'create'])->name('create');
            Route::post('/',                        [ItemReturnController::class, 'store'])->name('store');
            Route::post('{itemReturn}/send',        [ItemReturnController::class, 'send'])->name('send');
            Route::post('{itemReturn}/confirm',     [ItemReturnController::class, 'confirm'])->name('confirm');
            Route::post('{itemReturn}/cancel',      [ItemReturnController::class, 'cancel'])->name('cancel');
        });

        Route::middleware('can:view return')->group(function () {
            Route::get('/',                         [ItemReturnController::class, 'index'])->name('index');
            Route::get('{itemReturn}',              [ItemReturnController::class, 'show'])->name('show');
        });
    });

    // ─── Laporan ───────────────────────────────────────────────
    Route::prefix('reports')->name('reports.')->middleware('can:view report')->group(function () {
        Route::get('/',             [ReportController::class, 'index'])->name('index');
        Route::get('/stock',        [ReportController::class, 'stock'])->name('stock');
        Route::get('/movements',    [ReportController::class, 'movements'])->name('movements');
        Route::get('/purchase-orders', [ReportController::class, 'purchaseOrders'])->name('purchase-orders');
        Route::get('/distributions', [ReportController::class, 'distributions'])->name('distributions');
        Route::get('/returns',      [ReportController::class, 'returns'])->name('returns');

        Route::middleware('can:export report')->group(function () {
            Route::get('/stock/export/{format}',           [ReportController::class, 'exportStock'])->name('stock.export');
            Route::get('/movements/export/{format}',       [ReportController::class, 'exportMovements'])->name('movements.export');
            Route::get('/purchase-orders/export/{format}', [ReportController::class, 'exportPurchaseOrders'])->name('purchase-orders.export');
            Route::get('/distributions/export/{format}',   [ReportController::class, 'exportDistributions'])->name('distributions.export');
            Route::get('/returns/export/{format}',         [ReportController::class, 'exportReturns'])->name('returns.export');
        });
    });

    // ─── User Management ───────────────────────────────────────
    Route::prefix('users')->name('users.')->middleware('can:manage users')->group(function () {
        Route::get('/',                [UserController::class, 'index'])->name('index');
        Route::get('/create',          [UserController::class, 'create'])->name('create');
        Route::post('/',               [UserController::class, 'store'])->name('store');
        Route::get('/{user}',          [UserController::class, 'show'])->name('show');
        Route::get('/{user}/edit',     [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}',          [UserController::class, 'update'])->name('update');
        Route::delete('/{user}',       [UserController::class, 'destroy'])->name('destroy');
        Route::post('/{user}/toggle',  [UserController::class, 'toggle'])->name('toggle');
    });

    // ─── Profile ───────────────────────────────────────────────
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/',         [ProfileController::class, 'index'])->name('index');
        Route::put('/',         [ProfileController::class, 'update'])->name('update');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password');
    });
});

require __DIR__ . '/auth.php';
