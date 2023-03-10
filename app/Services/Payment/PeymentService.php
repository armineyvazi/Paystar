<?php

namespace App\Services\Payment;

use App\Http\Requests\PayRequest;
use App\Models\Product;
use App\Models\Transaction;
use App\Repository\Invoice\InvoiceRepositoryInterface;
use App\Repository\Order\OrderRepositoryInterface;
use App\Repository\Transaction\TransactionRepositoryInterface;
use App\Services\PaymentStrategy\PaymentStrategyServiceInterface;

class PeymentService implements PaymentServiceInterface
{
    /**
     * @var PaymentStrategyServiceInterface
     */
    protected PaymentStrategyServiceInterface $paymentStrategyService;

    /**
     * @var InvoiceRepositoryInterface
     */
    private InvoiceRepositoryInterface $invoice;

    /**
     * @var OrderRepositoryInterface
     */
    private OrderRepositoryInterface $order;

    /**
     * @var TransactionRepositoryInterface
     */
    private TransactionRepositoryInterface $transaction;

    /**
     * @param PaymentStrategyServiceInterface $paymentStrategyService
     * @param InvoiceRepositoryInterface $invoice
     * @param OrderRepositoryInterface $order
     * @param TransactionRepositoryInterface $transaction
     */
    public function __construct(
        PaymentStrategyServiceInterface $paymentStrategyService,
        InvoiceRepositoryInterface $invoice,
        OrderRepositoryInterface $order,
        TransactionRepositoryInterface $transaction,
    ) {
        $this->paymentStrategyService = $paymentStrategyService;
        $this->invoice = $invoice;
        $this->order = $order;
        $this->transaction = $transaction;
    }

    /**
     * @see PaymentServiceInterface::pay()
     *
     * @throws \Exception
     */
    public function pay(Product $product, PayRequest $request): string|\Exception
    {
        $product = $product->find($request->product)?->first();

        $order = $this->order->create($product->id, $product->price);

        $invoice = $this->invoice->create($order->id, $order->total);

        $redirectUrl = $this->paymentStrategyService->pay($invoice);

        if (! str_contains($redirectUrl, $this->paymentStrategyService::REDRICET)) {
            return throw new \Exception($redirectUrl);
        }

        return $redirectUrl;
    }

    /**
     * @see PaymentServiceInterface::callback()
     * @param $requst
     * @return array|\Exception
     * @throws \Exception
     */
    public function callback($requst): array|\Exception
    {
        $transaction = $this->transaction->create($requst);

        $callback = $this->paymentStrategyService->callback($transaction);

        if (is_string($callback)) {
            return throw new \Exception($callback);
        }

        $amount = $this->invoice->getAmount($requst->ref_num);

        $verifyData = [
            'refNumber' => $transaction->ref_num,
            'amount' => $amount,
            'cardNumber' => $transaction->card_number,
            'trackingCode' => $transaction->tracking_code,
        ];

        $verify = $this->paymentStrategyService->verify($verifyData);

        if (is_string($verify)) {
            $transaction->status = Transaction::FAIL;
            $transaction->save();

            return throw new \Exception($verify);
        }

        $transaction->status = Transaction::OK;
        $transaction->save();

        $this->invoice->updateTrackingCode($transaction->ref_num, $transaction->tracking_code);

        $data = [
            'trackingCode' => $transaction->tracking_code,
            'amount' => $amount,
            'message' => 'Payment Success',
        ];

        return  $data;
    }
}
