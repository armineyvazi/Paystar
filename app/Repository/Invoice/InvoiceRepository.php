<?php

namespace App\Repository\Invoice;

use App\Models\Invoice;

class InvoiceRepository implements InvoiceRepositoryInterface
{
    private Invoice $model;

    public function __construct(Invoice $model)
    {
        $this->model = $model;
    }

    /**
     * @see InvoiceRepositoryInterface::create()
     */
    public function create(int $orderId, int $total): Invoice
    {
        $this->model->user_id = auth()->user()->id;
        $this->model->name_customer = auth()->user()->name;
        $this->model->order_id = $orderId;
        $this->model->amount = $total;
        $this->model->payment_amount = $total;

        return $this->model;
    }

    /**
     * @see InvoiceRepositoryInterface::getAmount()
     *
     * @return mixed
     */
    public function getAmount($refNum)
    {
        return $this->model->where('ref_num', $refNum)->first()->amount;
    }

    /**
     * @see  InvoiceRepositoryInterface::updateTrackingCode()
     */
    public function updateTrackingCode(string $refNum, int $trackingCode): void
    {
        $this->model->where('ref_num', $refNum)->update(['tracking_code' => $trackingCode]);
    }
}
