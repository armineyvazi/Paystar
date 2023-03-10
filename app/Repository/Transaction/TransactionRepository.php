<?php

namespace App\Repository\Transaction;

use App\Models\Transaction;

class TransactionRepository implements TransactionRepositoryInterface
{
    private Transaction $model;

    public function __construct(Transaction $model)
    {
        $this->model = $model;
    }

    /**
     * @param $request
     * @return Transaction
     */
    public function create($request): Transaction
    {
        $transaction = $this->model->create([
            'user_id' => auth()->user()->id,
            'order_id' => $request->order_id,
            'status' => $request->status,
            'card_number' => $request->card_number ?? null,
            'service_transaction_id' => $request->transaction_id ?? null,
            'ref_num' => $request->ref_num,
            'tracking_code' => $request->tracking_code ?? null,
        ]);

        return $transaction;
    }

    /**
     * @return void
     */
    public function save(): void
    {
        $this->model->save();
    }
}
