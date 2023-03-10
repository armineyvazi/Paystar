<?php

namespace App\Repository\Order;

use App\Models\Order;

interface OrderRepositoryInterface
{
    /**
     * @param int $prdocutId
     * @param int $total
     * @return Order
     */
    public function create(int $prdocutId, int $total): Order;
}
