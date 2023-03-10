<?php

namespace App\Repository\Transaction;

use App\Models\Transaction;

interface TransactionRepositoryInterface
{
    /**
     * @param $request
     * @return Transaction
     */
    public function create($request): Transaction;

    /**
     * @return void
     */
    public function save(): void;
}
