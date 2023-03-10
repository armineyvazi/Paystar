<?php

namespace App\Repository\Invoice;

use App\Models\Invoice;

interface InvoiceRepositoryInterface
{
    public function create(int $orderId, int $total): Invoice;

    /**
     * @return mixed
     */
    public function getAmount($refNum);

    /**
     * @param string $refNumb
     * @param int $trackingCode
     * @return void
     */
    public function updateTrackingCode(string $refNumb, int $trackingCode): void;
}
