<?php

namespace App\Services\PaymentStrategy;

use App\Models\Invoice;
use App\Models\Transaction;

interface PaymentStrategyServiceInterface
{
    /**
     * @param Invoice $invoice
     * @return string
     */
    public function pay(Invoice $invoice): string;

    /**
     * @param Transaction $transaction
     * @return string|bool
     */
    public function callback(Transaction $transaction): string|bool;

    /**
     * @param array $arg
     * @return bool|string
     */
    public function verify(array $arg): bool|string;
}
