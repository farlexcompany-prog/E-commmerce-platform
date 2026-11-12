<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminOrderManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_admin_sign_in(): void
    {
        $this->get(route('admin.home'))
            ->assertRedirect(route('admin.dashboard'));

        $this->get(route('admin.dashboard'))
            ->assertRedirect(route('admin.login'));
    }

    public function test_customer_sessions_cannot_view_admin_orders(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('admin.dashboard'))
            ->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_search_orders_and_view_customer_and_product_details(): void
    {
        $admin = $this->makeAdmin();
        $order = Order::query()->create([
            'order_number' => 'FARLEXORDER123456',
            'customer_name' => 'Maya Patel',
            'customer_email' => 'maya@example.com',
            'customer_phone' => '9876543210',
            'shipping_address' => '21 Market Road',
            'pincode' => '160001',
            'payment_method' => 'upi',
            'mrp_total' => 250,
            'discount' => 25,
            'delivery_fee' => 0,
            'total' => 225,
            'status' => 'demo_order_pending',
        ]);
        $order->items()->create([
            'product_name' => 'Magnesium Capsules',
            'quantity' => 2,
            'unit_price' => 112.50,
            'line_total' => 225,
        ]);

        $this->actingAs($admin, 'admin')
            ->get(route('admin.dashboard', ['q' => 'Magnesium']))
            ->assertOk()
            ->assertSee('Dashboard')
            ->assertSee('Catalog')
            ->assertSee('Attribute Families')
            ->assertSee(route('admin.sections.show', 'products'))
            ->assertSee('Maya Patel')
            ->assertSee('maya@example.com')
            ->assertSee('Magnesium Capsules')
            ->assertSee('FARLEXORDER123456');

        $this->actingAs($admin, 'admin')
            ->get(route('admin.orders.show', $order->order_number))
            ->assertOk()
            ->assertSee('21 Market Road')
            ->assertSee('9876543210')
            ->assertSee('Products ordered')
            ->assertSee('Magnesium Capsules');
    }

    public function test_admin_navigation_sections_open_demo_pages(): void
    {
        $admin = $this->makeAdmin();

        foreach (config('admin.sections') as $slug => $section) {
            $this->actingAs($admin, 'admin')
                ->get(route('admin.sections.show', $slug))
                ->assertOk()
                ->assertSee($section['title'])
                ->assertSee('Demo page');
        }

        $this->actingAs($admin, 'admin')
            ->get(route('admin.sections.show', 'not-a-section'))
            ->assertNotFound();
    }

    public function test_admin_login_rejects_non_admin_accounts(): void
    {
        $response = $this->post(route('admin.login.store'), [
            'username' => 'unknown-admin',
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors('username');
        $this->assertGuest('admin');
    }

    public function test_admin_can_sign_in_with_valid_credentials(): void
    {
        $admin = $this->makeAdmin();

        $this->post(route('admin.login.store'), [
            'username' => $admin->username,
            'password' => 'password',
        ])
            ->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($admin, 'admin');
    }

    public function test_admin_account_can_be_created_with_a_confirmed_password(): void
    {
        $password = 'secure-password-123';

        $this->artisan('admin:create', ['username' => 'order-manager'])
            ->expectsQuestion('Password (at least 12 characters)', $password)
            ->expectsQuestion('Confirm password', $password)
            ->expectsOutput('Admin account created. Sign in at /admin/login.')
            ->assertExitCode(0);

        $admin = Admin::query()->where('username', 'order-manager')->firstOrFail();
        $this->assertTrue(Hash::check($password, $admin->password));
    }

    private function makeAdmin(): Admin
    {
        $admin = new Admin;
        $admin->username = 'order-manager';
        $admin->password = Hash::make('password');
        $admin->save();

        return $admin;
    }
}
