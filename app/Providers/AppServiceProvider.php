<?php

namespace App\Providers;

use App\Services\StockService;
use Illuminate\Support\ServiceProvider;

use App\Services\PurchaseOrderService;
use App\Services\DistributionService;

use App\Services\ReturnService;

use Illuminate\Support\Facades\App;
use Carbon\Carbon;

use App\Services\ReportService;

use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(StockService::class);
        $this->app->singleton(PurchaseOrderService::class);
        $this->app->singleton(DistributionService::class);
        $this->app->singleton(ReturnService::class);
        $this->app->singleton(ReportService::class);
    }
    public function boot(): void
    {
        Carbon::setLocale('id');
        App::setLocale('id');
        Paginator::useTailwind(); // pastikan pakai Tailwind
    }
}
