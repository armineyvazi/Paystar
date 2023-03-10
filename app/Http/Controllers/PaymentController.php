<?php

namespace App\Http\Controllers;

use App\Http\Requests\PayRequest;
use App\Models\Product;
use App\Services\Payment\PaymentServiceInterface;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected PaymentServiceInterface $paymentService;

    public function __construct(
        PaymentServiceInterface $paymentService
    ) {
        $this->paymentService = $paymentService;
    }

    public function pay(PayRequest $request, Product $product)
    {
        try {
            $redirectUrl = $this->paymentService->pay($product, $request);
        } catch (\Exception $e) {
            return view('checkout', ['error' => $e->getMessage()]);
        }

        return redirect($redirectUrl, 302);
    }

    public function callback(Request $request)
    {
        try {
            $data = $this->paymentService->callback($request);
        } catch (\Exception $e) {
            return view('transactionfailed', ['error' => $e->getMessage()]);
        }

        return view('/transactionSuccess', compact('data'));
    }
}
