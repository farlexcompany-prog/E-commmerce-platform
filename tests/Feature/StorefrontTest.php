<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StorefrontTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_listing_filters_rx_items(): void
    {
        $this->seed();

        $this->get(route('shop', ['rx' => 'rx']))
            ->assertOk()
            ->assertSee('Sample Rx Tablet Pack')
            ->assertDontSee('Sample OTC Tablet Pack');
    }

    public function test_otc_customer_can_place_a_demo_order_with_coupon(): void
    {
        $this->seed();
        $product = Product::query()->where('slug', 'sample-otc-tablet-pack')->firstOrFail();

        $this->post(route('cart.store', $product), ['quantity' => 2])
            ->assertRedirect();
        $this->post(route('coupon.apply'), ['coupon_code' => 'FIRST10'])
            ->assertRedirect();
        $this->get(route('checkout'))->assertOk();

        $response = $this->post(route('checkout.store'), [
            'customer_name' => 'Taylor Customer',
            'customer_email' => 'taylor@example.com',
            'customer_phone' => '9876543210',
            'shipping_address' => '123 Market Street',
            'pincode' => '110001',
            'payment_method' => 'upi',
            'accept_terms' => '1',
        ]);

        $order = Order::query()->firstOrFail();
        $response->assertRedirect(route('orders.show', $order->order_number));
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'total' => '308.20',
            'mrp_total' => '358.00',
            'discount' => '89.80',
            'coupon_code' => 'FIRST10',
            'status' => 'demo_order_pending',
        ]);
        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'line_total' => '298.00',
        ]);
        $response->assertSessionMissing('cart');
        $this->get(route('orders.show', $order->order_number))
            ->assertOk()
            ->assertSee($order->order_number)
            ->assertSee('Sample OTC Tablet Pack')
            ->assertDontSee('taylor@example.com');
    }

    public function test_rx_checkout_requires_a_valid_prescription_upload(): void
    {
        Storage::fake('local');
        $this->seed();
        $product = Product::query()->where('slug', 'sample-rx-tablet-pack')->firstOrFail();
        $this->post(route('cart.store', $product))->assertRedirect();

        $this->from(route('checkout'))
            ->post(route('checkout.store'), $this->checkoutData())
            ->assertRedirect(route('checkout'))
            ->assertSessionHasErrors('prescription');
        $this->assertDatabaseCount('orders', 0);

        $response = $this->post(route('checkout.store'), array_merge(
            $this->checkoutData(),
            ['prescription' => UploadedFile::fake()->create('prescription.pdf', 100, 'application/pdf')],
        ));

        $order = Order::query()->firstOrFail();
        $response->assertRedirect(route('orders.show', $order->order_number));
        $this->assertSame('pending_pharmacist_review', $order->status);
        $this->assertNotNull($order->prescription_path);
        Storage::disk('local')->assertExists($order->prescription_path);
    }

    public function test_wishlist_is_stored_in_the_current_session(): void
    {
        $this->seed();
        $product = Product::query()->where('slug', 'sample-digital-thermometer')->firstOrFail();

        $this->post(route('wishlist.toggle', $product))->assertRedirect();
        $this->get(route('wishlist.index'))->assertOk()->assertSee($product->name);

        $this->post(route('wishlist.toggle', $product))->assertRedirect();
        $this->get(route('wishlist.index'))->assertOk()->assertSee('No saved products yet.');
    }

    public function test_otp_page_does_not_claim_to_send_an_unconfigured_code(): void
    {
        $this->from(route('login'))
            ->post(route('login.otp'), ['phone' => '9876543210'])
            ->assertRedirect(route('login'))
            ->assertSessionHas('otp_notice', 'OTP sign-in is not enabled. Connect an approved SMS provider before accepting customer accounts.');
    }

    private function checkoutData(): array
    {
        return [
            'customer_name' => 'Taylor Customer',
            'customer_email' => 'taylor@example.com',
            'customer_phone' => '9876543210',
            'shipping_address' => '123 Market Street',
            'pincode' => '110001',
            'payment_method' => 'upi',
            'accept_terms' => '1',
        ];
    }
}
