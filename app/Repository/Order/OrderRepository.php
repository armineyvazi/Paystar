<?php

namespace App\Repository\Order;

use App\Models\Order;

class OrderRepository implements OrderRepositoryInterface
{
    private Order $model;

    /**
     * OrderRepository construct
     */
    public function __construct(Order $order)
    {
        $this->model = $order;
    }

    /**
     * @param int $prdocutId
     * @param int $total
     * @return Order
     */
    public function create(int $prdocutId, int $total): Order
    {
        $order = $this->model->create([
            'user_id' => auth()->user()->id,
            'product_id' => $prdocutId,
            'total' => $total,
        ]);

        return $order;
    }
}
