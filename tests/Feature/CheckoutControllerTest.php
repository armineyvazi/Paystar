<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CheckoutControllerTest extends TestCase
{
    use WithFaker,RefreshDatabase;

    /**
     * @test
     */
    public function checkout_screen_can_be_rendered()
    {
        $user = User::factory()->create();
        //Auth
        $this->actingAs($user);
        Product::factory()->create();

        $response = $this->get('/checkout');
        $response->assertStatus(200);
    }
}
