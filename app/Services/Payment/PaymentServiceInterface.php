<?php

namespace App\Services\Payment;

use App\Http\Requests\PayRequest;
use App\Models\Product;
use Illuminate\Http\Request;

interface PaymentServiceInterface
{
    /**
     * @param Product $product
     * @param PayRequest $request
     * @return string|\Exception
     */
    public function pay(Product $product, PayRequest $request): string|\Exception;

    /**
     * @param Request $requst
     * @return array|\Exception
     */
    public function callback(Request $requst): array|\Exception;
}
