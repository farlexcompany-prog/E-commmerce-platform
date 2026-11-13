<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Admin;
use App\Models\CmsPage;
use App\Models\CmsReview;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
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

        $this->get(route('admin.catalog.products'))
            ->assertRedirect(route('admin.login'));

        $this->get(route('admin.catalog.products.create'))
            ->assertRedirect(route('admin.login'));

        $this->get(route('admin.sales.orders'))
            ->assertRedirect(route('admin.login'));

        $this->get(route('admin.pages.index'))
            ->assertRedirect(route('admin.login'));

        $this->get(route('admin.reviews.index'))
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
            ->assertSee(route('admin.catalog.products'))
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

        $this->actingAs($admin, 'admin')
            ->get(route('admin.sales.orders', ['q' => 'Magnesium', 'per_page' => 10]))
            ->assertOk()
            ->assertSee('1 Results')
            ->assertSee('Pay by · Upi')
            ->assertSee('Farlex Store')
            ->assertSee('Magnesium Capsules');

        $export = $this->actingAs($admin, 'admin')
            ->get(route('admin.orders.export', ['q' => 'Magnesium']));
        $export->assertDownload('farlex-orders.csv');
        $this->assertStringContainsString('Maya Patel', $export->streamedContent());
    }

    public function test_sales_orders_page_matches_the_orders_workspace_and_hides_removed_navigation(): void
    {
        $admin = $this->makeAdmin();

        $this->actingAs($admin, 'admin')
            ->get(route('admin.sales.orders'))
            ->assertOk()
            ->assertSee('Orders')
            ->assertSee('Grand Total / Pay Via / Channel')
            ->assertSee('Customer / Email / Location')
            ->assertSee('Filter')
            ->assertSee('Per Page')
            ->assertSee('Shipments')
            ->assertSee('Invoices')
            ->assertSee('Refunds')
            ->assertSee('Transactions')
            ->assertSee('Bookings')
            ->assertDontSee('Marketing')
            ->assertDontSee('Reporting')
            ->assertDontSee('Settings')
            ->assertDontSee('Configure');

        $this->actingAs($admin, 'admin')
            ->get(route('admin.sections.show', 'sales'))
            ->assertRedirect(route('admin.sales.orders'));

        foreach (['settings', 'configure'] as $removedSection) {
            $this->actingAs($admin, 'admin')
                ->get(route('admin.sections.show', $removedSection))
                ->assertNotFound();
        }
    }

    public function test_admin_can_filter_products_in_the_catalog(): void
    {
        $admin = $this->makeAdmin();
        $visibleProduct = Product::query()->create([
            'name' => 'Sample OTC Tablet Pack',
            'slug' => 'sample-otc-tablet-pack',
            'description' => 'Reviewed demo product information.',
            'category' => 'Medicines',
            'brand' => 'Farlex Demo',
            'dosage_form' => 'Tablet',
            'pack_size' => '10 tablets',
            'price' => 149,
            'mrp' => 179,
            'stock' => 12,
            'rx_required' => false,
            'active' => true,
        ]);
        Product::query()->create([
            'name' => 'Inactive Rx Capsule',
            'slug' => 'inactive-rx-capsule',
            'description' => 'Reviewed demo product information.',
            'category' => 'Medicines',
            'brand' => 'Farlex Demo',
            'dosage_form' => 'Capsule',
            'price' => 199,
            'stock' => 0,
            'rx_required' => true,
            'active' => false,
        ]);

        $this->actingAs($admin, 'admin')
            ->get(route('admin.catalog.products', [
                'q' => 'Sample',
                'category' => 'Medicines',
                'status' => 'active',
                'rx' => 'otc',
            ]))
            ->assertOk()
            ->assertSee($visibleProduct->name)
            ->assertSee('12 units')
            ->assertDontSee('Inactive Rx Capsule')
            ->assertSee('Total products');
    }

    public function test_admin_can_edit_and_reorder_storefront_content_and_upload_a_banner(): void
    {
        Storage::fake('public');
        $admin = $this->makeAdmin();

        $this->get(route('store'))->assertOk();
        $this->actingAs($admin, 'admin')
            ->get(route('admin.pages.index'))
            ->assertOk()
            ->assertSee('Pages')
            ->assertSee('Home page')
            ->assertSee('Privacy policy');

        $payload = $this->cmsPagePayload('home');
        $payload['content']['fields']['banner_one']['title'] = 'CMS featured banner';
        $payload['content']['sections']['categories']['order'] = 1;
        $payload['content']['sections']['categories']['enabled'] = false;
        $payload['media']['banner_one']['image'] = UploadedFile::fake()->createWithContent(
            'homepage-banner.png',
            file_get_contents(public_path('images/farlex-logo.png')),
        );

        $this->actingAs($admin, 'admin')
            ->put(route('admin.pages.update', 'home'), $payload)
            ->assertRedirect(route('admin.pages.edit', 'home'))
            ->assertSessionHas('status', 'Page content updated.');

        $page = CmsPage::query()->where('page_key', 'home')->firstOrFail();
        $this->assertSame('CMS featured banner', $page->content['fields']['banner_one']['title']);
        $this->assertSame(1, $page->content['sections']['categories']['order']);
        $this->assertFalse($page->content['sections']['categories']['enabled']);
        Storage::disk('public')->assertExists($page->content['fields']['banner_one']['image']);

        $this->get(route('store'))
            ->assertOk()
            ->assertSee('CMS featured banner')
            ->assertSee(Storage::disk('public')->url($page->content['fields']['banner_one']['image']), false)
            ->assertDontSee('<h2>Shop by category</h2>', false);

        $policyPayload = $this->cmsPagePayload('privacy');
        $policyPayload['content']['fields']['policy']['intro'] = 'Customized privacy policy introduction.';
        $this->actingAs($admin, 'admin')
            ->put(route('admin.pages.update', 'privacy'), $policyPayload)
            ->assertRedirect(route('admin.pages.edit', 'privacy'));
        $this->get(route('policies.show', 'privacy'))
            ->assertOk()
            ->assertSee('Customized privacy policy introduction.');
    }

    public function test_admin_cannot_save_unsafe_page_links(): void
    {
        $admin = $this->makeAdmin();
        $payload = $this->cmsPagePayload('home');
        $payload['content']['fields']['banner_one']['button_url'] = 'javascript:alert(1)';

        $this->actingAs($admin, 'admin')
            ->put(route('admin.pages.update', 'home'), $payload)
            ->assertSessionHasErrors('content.fields.banner_one.button_url');

        $this->assertDatabaseMissing('cms_pages', ['page_key' => 'home']);
    }

    public function test_admin_can_publish_a_youtube_and_image_review_to_the_homepage_carousel(): void
    {
        Storage::fake('public');
        $admin = $this->makeAdmin();
        $reviewData = [
            'reviewer_name' => 'A Customer',
            'video_title' => 'Customer review video title',
            'review_text' => 'The service was helpful and delivery was prompt.',
            'youtube_url' => 'https://youtu.be/abcDEF123_-',
            'image' => UploadedFile::fake()->createWithContent(
                'review-image.png',
                file_get_contents(public_path('images/farlex-logo.png')),
            ),
            'position' => '2',
            'permission_confirmed' => '1',
            'published' => '1',
        ];
        $this->actingAs($admin, 'admin')
            ->get(route('admin.reviews.create'))
            ->assertOk()
            ->assertSee('YouTube video URL')
            ->assertSee('permission to publish');

        $this->actingAs($admin, 'admin')
            ->post(route('admin.reviews.store'), $reviewData)
            ->assertRedirect(route('admin.reviews.index'))
            ->assertSessionHas('status', 'Review added.');

        $review = CmsReview::query()->where('youtube_url', $reviewData['youtube_url'])->firstOrFail();
        $this->assertTrue($review->published);
        $this->assertTrue($review->permission_confirmed);
        $this->assertSame('Customer review video title', $review->video_title);
        $this->assertSame('https://www.youtube-nocookie.com/embed/abcDEF123_-', $review->youtube_embed_url);
        Storage::disk('public')->assertExists($review->image_path);

        $this->actingAs($admin, 'admin')
            ->get(route('admin.reviews.index'))
            ->assertOk()
            ->assertSee('A Customer')
            ->assertSee('Published');

        $this->get(route('store'))
            ->assertOk()
            ->assertSee('Farlex Pharma videos')
            ->assertSee('Customer Testimonial 3')
            ->assertSee('https://www.youtube-nocookie.com/embed/S0FoTOvtQm4', false)
            ->assertSee('Customer review video title')
            ->assertSee('The service was helpful and delivery was prompt.')
            ->assertSee('A Customer')
            ->assertSee($review->youtube_embed_url, false)
            ->assertSee(Storage::disk('public')->url($review->image_path), false)
            ->assertDontSee('Google Play')
            ->assertDontSee('App Store')
            ->assertDontSee('app downloads');

        $this->actingAs($admin, 'admin')
            ->put(route('admin.reviews.update', $review), [
                'reviewer_name' => 'A Customer',
                'video_title' => null,
                'review_text' => $reviewData['review_text'],
                'youtube_url' => $reviewData['youtube_url'],
                'position' => '2',
                'permission_confirmed' => '1',
                'published' => '0',
            ])
            ->assertRedirect(route('admin.reviews.index'));

        $this->get(route('store'))
            ->assertOk()
            ->assertDontSee('The service was helpful and delivery was prompt.');
    }

    public function test_admin_cannot_publish_reviews_from_untrusted_video_hosts_or_without_permission(): void
    {
        $admin = $this->makeAdmin();
        $reviewData = [
            'reviewer_name' => 'A Customer',
            'review_text' => 'A genuine review.',
            'youtube_url' => 'https://youtube.com.attacker.example/watch?v=abcDEF123_-',
            'position' => '0',
            'permission_confirmed' => '1',
            'published' => '1',
        ];
        $invalidUrl = $reviewData['youtube_url'];

        $this->actingAs($admin, 'admin')
            ->post(route('admin.reviews.store'), $reviewData)
            ->assertSessionHasErrors('youtube_url');

        $reviewData['youtube_url'] = '';
        $reviewData['permission_confirmed'] = '0';
        $this->actingAs($admin, 'admin')
            ->post(route('admin.reviews.store'), $reviewData)
            ->assertSessionHasErrors('permission_confirmed');

        $this->assertDatabaseMissing('cms_reviews', [
            'youtube_url' => $invalidUrl,
        ]);
    }

    public function test_admin_can_create_and_update_product_descriptions(): void
    {
        Storage::fake('public');

        $admin = $this->makeAdmin();
        $productData = [
            'name' => 'Farlex Iron Capsules',
            'slug' => '',
            'description' => 'Product description reviewed against the approved package.',
            'category' => 'Supplements',
            'brand' => 'Farlex',
            'manufacturer' => 'Farlex Pharmaceuticals',
            'pack_size' => '30 capsules',
            'dosage_form' => 'Capsule',
            'rx_required' => '1',
            'price' => '149.00',
            'mrp' => '179.00',
            'stock' => '24',
            'art_style' => 'capsule',
            'image' => UploadedFile::fake()->createWithContent(
                'farlex-iron.png',
                file_get_contents(public_path('images/farlex-logo.png')),
            ),
            'video' => UploadedFile::fake()->createWithContent(
                'farlex-iron.mp4',
                "\x00\x00\x00\x18ftypmp42\x00\x00\x00\x00mp42isom",
            ),
            'details' => [
                'composition' => 'Iron 10 mg',
                'uses' => 'Use only as directed by a healthcare professional.',
                'dosage' => 'Follow the approved product label.',
                'side_effects' => 'See approved product information.',
                'warnings' => 'Consult a registered medical practitioner.',
                'storage' => 'Store as directed on the package.',
                'faqs' => 'Ask a registered pharmacist for product-specific advice.',
            ],
        ];

        $this->actingAs($admin, 'admin')
            ->get(route('admin.catalog.products.create'))
            ->assertOk()
            ->assertSee('Composition / salt')
            ->assertSee('Warnings and precautions')
            ->assertSee('Required product photos and video')
            ->assertSee('Product video *')
            ->assertSee('multipart/form-data');

        $missingMedia = $productData;
        unset($missingMedia['image'], $missingMedia['video']);
        $this->actingAs($admin, 'admin')
            ->post(route('admin.catalog.products.store'), $missingMedia)
            ->assertSessionHasErrors(['image', 'video']);

        $this->actingAs($admin, 'admin')
            ->post(route('admin.catalog.products.store'), $productData)
            ->assertRedirect(route('admin.catalog.products'))
            ->assertSessionHas('status', 'Product added to the catalog.');

        $product = Product::query()->where('slug', 'farlex-iron-capsules')->firstOrFail();
        $this->assertSame('Iron 10 mg', $product->details['composition']);
        $this->assertTrue($product->rx_required);
        $this->assertFalse($product->active);
        Storage::disk('public')->assertExists($product->image_path);
        Storage::disk('public')->assertExists($product->video_path);

        $product->update(['active' => true]);
        $this->get(route('products.show', $product))
            ->assertOk()
            ->assertSee(Storage::disk('public')->url($product->image_path), false)
            ->assertSee(Storage::disk('public')->url($product->video_path), false);
        $originalImagePath = $product->image_path;
        $originalVideoPath = $product->video_path;

        $this->actingAs($admin, 'admin')
            ->get(route('admin.catalog.products.edit', $product))
            ->assertOk()
            ->assertDontSee('Product photo *')
            ->assertDontSee('Product video *')
            ->assertDontSee('name="image"', false)
            ->assertDontSee('name="video"', false);

        $productData['name'] = 'Farlex Iron Tablets';
        $productData['rx_required'] = '0';
        $productData['active'] = '1';
        $productData['details']['composition'] = 'Iron 20 mg';
        unset($productData['image'], $productData['video']);

        $this->actingAs($admin, 'admin')
            ->put(route('admin.catalog.products.update', $product), $productData)
            ->assertRedirect(route('admin.catalog.products'))
            ->assertSessionHas('status', 'Product details updated.');

        $product->refresh();
        $this->assertSame('farlex-iron-tablets', $product->slug);
        $this->assertSame('Iron 20 mg', $product->details['composition']);
        $this->assertFalse($product->rx_required);
        $this->assertTrue($product->active);
        $this->assertSame($originalImagePath, $product->image_path);
        $this->assertSame($originalVideoPath, $product->video_path);
        Storage::disk('public')->assertExists($originalImagePath);
        Storage::disk('public')->assertExists($originalVideoPath);

        $this->get(route('products.show', $product))
            ->assertOk()
            ->assertSee('Product description reviewed against the approved package.')
            ->assertSee('Iron 20 mg')
            ->assertSee('Follow the approved product label.');
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

    private function cmsPagePayload(string $pageKey): array
    {
        $definition = config("cms.pages.{$pageKey}");
        $payload = ['content' => ['fields' => [], 'sections' => []]];

        foreach ($definition['sections'] as $sectionKey => $section) {
            if ($definition['reorderable'] ?? false) {
                $payload['content']['sections'][$sectionKey]['order'] = $section['order'];
            }
            if ($section['optional'] ?? false) {
                $payload['content']['sections'][$sectionKey]['enabled'] = true;
            }

            foreach ($section['fields'] as $fieldKey => $field) {
                if ($field['type'] !== 'image') {
                    $payload['content']['fields'][$sectionKey][$fieldKey] = $field['default'] ?? '';
                }
            }
        }

        return $payload;
    }
}
