<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StorefrontTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_add_to_cart_and_place_an_order(): void
    {
        $this->seed();
        $product = Product::query()->where('slug', 'everyday-backpack')->firstOrFail();

        $this->post(route('cart.store', $product), ['quantity' => 2])
            ->assertRedirect();
        $this->get(route('checkout'))->assertOk();

        $response = $this->post(route('checkout.store'), [
            'customer_name' => 'Taylor Customer',
            'customer_email' => 'taylor@example.com',
            'shipping_address' => '123 Market Street',
        ]);

        $order = Order::query()->firstOrFail();
        $response->assertRedirect(route('orders.show', $order->order_number));
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'total' => '118.00',
        ]);
        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'line_total' => '118.00',
        ]);
        $response->assertSessionMissing('cart');
        $this->get(route('orders.show', $order->order_number))
            ->assertOk()
            ->assertSee($order->order_number)
            ->assertSee('Everyday Backpack');
    }

    public function test_invalid_cart_quantity_is_rejected(): void
    {
        $this->seed();
        $product = Product::query()->where('slug', 'everyday-backpack')->firstOrFail();

        $this->from(route('store'))
            ->post(route('cart.store', $product), ['quantity' => 0])
            ->assertRedirect(route('store'))
            ->assertSessionHasErrors('quantity');
    }
}
