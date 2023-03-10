<?php

namespace App\Services\PaymentStrategy;

use App\Models\Invoice;
use App\Models\Transaction;
use Illuminate\Support\Facades\Http;

class PayStar implements PaymentStrategyServiceInterface
{
    /**
     * paystar create_url
     */
    private const CREATE_URL = 'https://core.paystar.ir/api/pardakht/create';

    /**
     * paystar verify_url
     */
    private const VERIFY_URL = 'https://core.paystar.ir/api/pardakht/verify';

    /**
     * paystar redirect_url
     */
    public const REDRICET = 'https://core.paystar.ir/api/pardakht/payment?token=';

    /**
     * @var string
     */
    protected string $refNum;

    /**
     * @var string
     */
    protected string $orderId;

    /**
     * @var string
     */
    protected string $trackingCode;

    /**
     * @see PaymentStrategyServiceInterface::pay()
     * @param Invoice $invoice
     * @return string
     */
    public function pay(Invoice $invoice): string
    {
        $parameters = $this->signCreate($invoice->payment_amount,
            $invoice->order_id, config('payment.routeCallback'));

        $client = Http::withHeaders($this->createHeaders())
            ->post(self::CREATE_URL, $this->payParametr($invoice->payment_amount, $invoice->order_id, $parameters));
        $clientData = json_decode(response($client)->content());

        if ($clientData->status === (int) \App\Enums\Transaction::OK->value) {
            if ($clientData->data->payment_amount === $invoice->payment_amount) {
                $invoice->ref_num = $clientData->data->ref_num;
                $invoice->name_customer = auth()->user()->name;
                $invoice->save();
            } else {
                return PayStarException::error(-1);
            }
        } else {
            return PayStarException::error($clientData->status);
        }

        return  self::REDRICET.$clientData->data->token;
    }

    /**
     * @param string $amount
     * @param int|null $orderId
     * @param string|null $callback
     * @return string
     */
    public function signCreate(string $amount, int $orderId = null, string $callback = null): string
    {
        return hash_hmac(
            'sha512',
            (float) $amount.
            '#'.$orderId.'#'.
            $callback,
            config('paystar.CLIEANT_SECRET'));
    }

    public function signVerifyCreate($amount, $refNum, $cardNumber, $trackingCode)
    {
        return hash_hmac('sha512', $amount
            .'#'.(string) $refNum
            .'#'.$cardNumber
            .'#'.$trackingCode,
            config('paystar.CLIEANT_SECRET'));
    }

    /**
     * @return array
     */
    public function createHeaders(): array
    {
        return [
            'Authorization' => config('paystar.CLIEANT_ID'),
            'Content-Type' => 'application/json',
        ];
    }

    /**
     * @param float $price
     * @param int $orderId
     * @param $sign
     * @return array
     */
    public function payParametr(float $price, int $orderId, $sign): array
    {
        return [
            'amount' => $price,
            'order_id' => $orderId,
            'callback' => config('payment.routeCallback'),
            'callback_method' => true,
            'sign' => $sign,
        ];
    }

    /**
     * @see PaymentStrategyServiceInterface::callback()
     * @param Transaction $transaction
     * @return string|bool
     */
    public function callback(Transaction $transaction): string|bool
    {
        if ($transaction->status === Transaction::OK) {
            $transaction->status = Transaction::WAITHING;
            $this->trackingCode = $transaction->tracking_code ?? 'null';
            $this->orderId = $transaction->order_id;
            $this->refNum = $transaction->ref_num;
            $transaction->save();
        } else {
            $transaction->status = Transaction::FAIL;
            $transaction->save();

            return PayStarException::error($transaction->status);
        }

        return true;
    }

    /**
     * @see PaymentStrategyServiceInterface::verify()
     * @param array $arg
     * @return bool|string
     */
    public function verify(array $arg): bool|string
    {
        $data = [
            'ref_num' => $this->refNum,
            'amount' => $arg['amount'],
            'sign' => $this->signVerifyCreate($arg['amount'], $this->refNum, $arg['cardNumber'], $this->trackingCode),
        ];

        $client = Http::withHeaders($this->createHeaders())
            ->post(self::VERIFY_URL, $data);
        $clientData = json_decode(response($client)->content());

        if ($clientData->status === Transaction::OK) {
            return  true;
        } else {
            return  PayStarException::error($clientData->status);
        }
    }
}
