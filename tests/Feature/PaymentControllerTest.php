<?php

namespace Tests\Feature;

use App\Models\Invoice;
use App\Models\Order;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class PaymentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    /**
     * @test
     */
    public function a_user_cant_store_invalid_product(): void
    {
        Product::factory()->create();
        $response = $this->post('/checkout/payment', [
            'cardNumber' => $this->user->card_number,
            'product' => 5456,
            'name' => $this->user->name,
        ]);
        $response->assertSessionHasErrors(['product']);
        $this->assertDatabaseCount('products', 1);
        $response->assertStatus(302);
    }

    /**
     * @test
     */
    public function user_create_order(): void
    {
        $product = Product::factory()->create();
        $response = $this->post('/checkout/payment', [
            'cardNumber' => $this->user->card_number,
            'product' => $product->id,
            'name' => $this->user->name,
        ]);
        $this->assertDatabaseCount('products', 1);
        $this->assertDatabaseCount('orders', 1);
        $response->assertStatus(302);
    }

    /**
     * @test
     */
    public function user_create_invoice()
    {
        $product = Product::factory()->create();
        $response = $this->post('/checkout/payment', [
            'cardNumber' => $this->user->card_number,
            'product' => $product->id,
            'name' => $this->user->name,
        ]);
        $this->assertDatabaseCount('products', 1);
        $this->assertDatabaseCount('invoices', 1);
        $response->assertStatus(302);
    }

    /**
     * @test
     */
    public function the_tracking_code_is_null_before_user_pay_the_order()
    {
        $product = Product::factory()->create();
        $response = $this->post('/checkout/payment', [
            'cardNumber' => $this->user->card_number,
            'product' => $product->id,
            'name' => $this->user->name,
        ]);
        $invoice = Invoice::first();
        $this->assertDatabaseCount('products', 1);
        $this->assertEquals($invoice->tracking_code, null);
        $this->assertDatabaseCount('invoices', 1);
        $response->assertStatus(302);
    }

    /**
     * @test
     */
    public function after_failing_pay_transaction_status_is_must_be_zero()
    {
        $product = Product::factory()->create();
        $this->post('/checkout/payment', [
            'cardNumber' => $this->user->card_number,
            'product' => $product->id,
            'name' => $this->user->name,
        ]);
        $invoice = Invoice::first();
        $order = Order::first();
        /**
         * create fake data
         */
        $transactionId = Str::random(5);
        $refNum = Str::random(5);
        $status = -98;

        $response = $this->get(route('getToken')."?status=$status&ref_num=$refNum&order_id=$order->id&transaction_id=$transactionId");
        $trans = Transaction::first();

        /**
         * expect
         */
        $this->assertEquals($trans->status, 0);
        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function after_redirect_to_home_if_status_is_one_trans_action_status_is_pending()
    {
        $product = Product::factory()->create();
        $this->post('/checkout/payment', [
            'cardNumber' => $this->user->card_number,
            'product' => $product->id,
            'name' => $this->user->name,
        ]);
        $invoice = Invoice::first();
        $order = Order::first();
        /**
         * create fake data
         */
        $transactionId = Str::random(5);
        $refNum = Str::random(5);
        $status = 1;

        $response = $this->get(route('getToken')."?status=$status&ref_num=$refNum&order_id=$order->id&transaction_id=$transactionId");
        $trans = Transaction::first();

        /**
         * expect
         */
        $this->assertEquals($trans->status, 2);
        $response->assertStatus(200);
    }
}
