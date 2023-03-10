<?php

namespace App\Providers;

use App\Repository\Invoice\InvoiceRepository;
use App\Repository\Invoice\InvoiceRepositoryInterface;
use App\Repository\Order\OrderRepository;
use App\Repository\Order\OrderRepositoryInterface;
use App\Repository\Transaction\TransactionRepository;
use App\Repository\Transaction\TransactionRepositoryInterface;
use App\Services\Payment\PaymentServiceInterface;
use App\Services\Payment\PeymentService;
use App\Services\PaymentStrategy\PaymentStrategyServiceInterface;
use Exception;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(InvoiceRepositoryInterface::class, InvoiceRepository::class);
        $this->app->bind(TransactionRepositoryInterface::class, TransactionRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->bind(PaymentServiceInterface::class, PeymentService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->bind(PaymentStrategyServiceInterface::class, function ($app) {
            $nameSpace = "App\Services\PaymentStrategy";
            $class = $nameSpace.'\\'.config('payment.method');

            if (class_exists($class)) {
                return  new $class;
            } else {
                throw new Exception("class $class not exist");
            }
        });
    }
}
