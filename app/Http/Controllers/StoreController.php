<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use App\Models\Order;
use App\Models\Product;
use App\Services\PageContent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use RuntimeException;
use Throwable;

class StoreController extends Controller
{
    public function index(PageContent $pageContent): View
    {
        $bannerSections = ['banner_one', 'banner_two', 'banner_three'];
        $banners = collect(config('store.banners'))
            ->values()
            ->map(function (array $banner, int $index) use ($pageContent, $bannerSections): ?array {
                $section = $bannerSections[$index] ?? null;
                if ($section === null || ! $pageContent->enabled('home', $section)) {
                    return null;
                }

                return [
                    'tone' => $banner['tone'],
                    'eyebrow' => $pageContent->value('home', $section, 'eyebrow', $banner['eyebrow']),
                    'title' => $pageContent->value('home', $section, 'title', $banner['title']),
                    'copy' => $pageContent->value('home', $section, 'copy', $banner['copy']),
                    'url' => $pageContent->value('home', $section, 'button_url', $banner['url']),
                    'cta' => $pageContent->value('home', $section, 'button_text', $banner['cta']),
                    'image' => $pageContent->imageUrl('home', $section, 'image'),
                    'order' => $pageContent->order('home', $section, $banner['order'] ?? (($index + 1) * 10)),
                ];
            })
            ->filter()
            ->sortBy('order')
            ->values()
            ->all();

        return view('store', [
            'featuredProducts' => Product::query()
                ->where('active', true)
                ->where('featured', true)
                ->orderBy('name')
                ->take(8)
                ->get(),
            'categories' => config('store.categories'),
            'concerns' => config('store.concerns'),
            'banners' => $banners,
        ]);
    }

    public function shop(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', 'string', 'max:80'],
            'brand' => ['nullable', 'string', 'max:80'],
            'form' => ['nullable', 'string', 'max:40'],
            'rx' => ['nullable', 'in:rx,otc'],
            'max_price' => ['nullable', 'numeric', 'min:0', 'max:100000'],
            'min_discount' => ['nullable', 'integer', 'min:0', 'max:90'],
            'min_rating' => ['nullable', 'numeric', 'min:0', 'max:5'],
            'sort' => ['nullable', 'in:relevance,price-low,price-high,discount,rating'],
        ]);

        $query = Product::query()->where('active', true);

        if (! empty($filters['q'])) {
            $term = trim($filters['q']);
            $query->where(function ($builder) use ($term) {
                $builder->where('name', 'like', '%'.$term.'%')
                    ->orWhere('category', 'like', '%'.$term.'%')
                    ->orWhere('brand', 'like', '%'.$term.'%');
            });
        }

        foreach (['category', 'brand'] as $field) {
            if (! empty($filters[$field])) {
                $query->where($field, $filters[$field]);
            }
        }

        if (! empty($filters['form'])) {
            $query->where('dosage_form', $filters['form']);
        }

        if (($filters['rx'] ?? null) === 'rx') {
            $query->where('rx_required', true);
        } elseif (($filters['rx'] ?? null) === 'otc') {
            $query->where('rx_required', false);
        }

        if (isset($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }

        if (! empty($filters['min_discount'])) {
            $query->whereNotNull('mrp')
                ->whereRaw('mrp > 0 AND ((mrp - price) * 100.0 / mrp) >= ?', [$filters['min_discount']]);
        }

        if (! empty($filters['min_rating'])) {
            $query->where('rating', '>=', $filters['min_rating']);
        }

        match ($filters['sort'] ?? 'relevance') {
            'price-low' => $query->orderBy('price'),
            'price-high' => $query->orderByDesc('price'),
            'discount' => $query->orderByRaw('(mrp - price) / NULLIF(mrp, 0) DESC'),
            'rating' => $query->orderByDesc('rating')->orderByDesc('review_count'),
            default => $query->orderByDesc('featured')->orderBy('name'),
        };

        return view('shop', [
            'products' => $query->paginate(12)->withQueryString(),
            'filters' => $filters,
            'categories' => Product::query()->where('active', true)->distinct()->orderBy('category')->pluck('category'),
            'brands' => Product::query()->where('active', true)->distinct()->orderBy('brand')->pluck('brand'),
            'forms' => Product::query()->where('active', true)->distinct()->orderBy('dosage_form')->pluck('dosage_form'),
        ]);
    }

    public function showProduct(Product $product): View
    {
        abort_unless($product->active, 404);

        return view('product', [
            'product' => $product,
            'similarProducts' => Product::query()
                ->where('active', true)
                ->where('category', $product->category)
                ->where('id', '<>', $product->id)
                ->take(4)
                ->get(),
            'frequentlyBought' => Product::query()
                ->where('active', true)
                ->where('featured', true)
                ->where('id', '<>', $product->id)
                ->take(3)
                ->get(),
        ]);
    }

    public function suggestions(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'q' => ['required', 'string', 'min:2', 'max:60'],
        ]);

        $term = trim($validated['q']);
        $matches = Product::query()
            ->where('active', true)
            ->where(function ($builder) use ($term) {
                $builder->where('name', 'like', '%'.$term.'%')
                    ->orWhere('category', 'like', '%'.$term.'%')
                    ->orWhere('brand', 'like', '%'.$term.'%');
            })
            ->orderBy('name')
            ->limit(6)
            ->get(['name', 'slug', 'category']);

        return response()->json($matches);
    }

    public function cart(): View
    {
        return view('cart', $this->cartSummary());
    }

    public function addToCart(Request $request, Product $product): RedirectResponse
    {
        abort_unless($product->active, 404);
        abort_if($product->stock < 1, 409, 'This demo item is currently out of stock.');

        $validated = $request->validate([
            'quantity' => ['sometimes', 'integer', 'min:1', 'max:99'],
            'go_to_checkout' => ['sometimes', 'in:1'],
        ]);

        $cart = session('cart', []);
        $requested = (int) ($validated['quantity'] ?? 1);
        $cart[$product->id] = min($product->stock, 99, (int) ($cart[$product->id] ?? 0) + $requested);
        session(['cart' => $cart]);

        if (isset($validated['go_to_checkout'])) {
            return redirect()->route('checkout');
        }

        return back()->with('status', 'Added to your cart.');
    }

    public function updateCart(Request $request, Product $product): RedirectResponse
    {
        abort_unless($product->active, 404);
        abort_if($product->stock < 1, 409, 'This demo item is currently out of stock.');

        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:'.$product->stock],
        ]);

        $cart = session('cart', []);
        if (array_key_exists($product->id, $cart)) {
            $cart[$product->id] = (int) $validated['quantity'];
            session(['cart' => $cart]);
        }

        return back()->with('status', 'Cart updated.');
    }

    public function removeFromCart(Product $product): RedirectResponse
    {
        $cart = session('cart', []);
        unset($cart[$product->id]);
        session(['cart' => $cart]);

        return back()->with('status', 'Item removed from your cart.');
    }

    public function applyCoupon(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'coupon_code' => ['required', 'string', 'max:30'],
        ]);

        if (strtoupper(trim($validated['coupon_code'])) !== config('store.coupon_code')) {
            return back()->withErrors(['coupon_code' => 'That sample offer code is not valid.']);
        }

        session(['coupon' => config('store.coupon_code')]);

        return back()->with('status', 'Sample offer applied. Confirm the final offer terms before launch.');
    }

    public function removeCoupon(): RedirectResponse
    {
        session()->forget('coupon');

        return back()->with('status', 'Offer code removed.');
    }

    public function toggleWishlist(Product $product): RedirectResponse
    {
        abort_unless($product->active, 404);

        $wishlist = session('wishlist', []);
        $key = (string) $product->id;

        if (isset($wishlist[$key])) {
            unset($wishlist[$key]);
            $message = 'Removed from your wishlist.';
        } else {
            $wishlist[$key] = true;
            $message = 'Added to your wishlist.';
        }

        session(['wishlist' => $wishlist]);

        return back()->with('status', $message);
    }

    public function wishlist(): View
    {
        $wishlist = session('wishlist', []);

        return view('wishlist', [
            'products' => Product::query()
                ->where('active', true)
                ->whereIn('id', array_keys($wishlist))
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function checkout(): View|RedirectResponse
    {
        $summary = $this->cartSummary();

        if ($summary['items']->isEmpty()) {
            return redirect()->route('shop')->with('status', 'Add an item before starting checkout.');
        }

        return view('checkout', $summary);
    }

    public function placeOrder(Request $request): RedirectResponse
    {
        $summary = $this->cartSummary();
        if ($summary['items']->isEmpty()) {
            return redirect()->route('shop')->with('status', 'Add an item before starting checkout.');
        }

        $rules = [
            'customer_name' => ['required', 'string', 'max:120'],
            'customer_email' => ['required', 'email', 'max:255'],
            'customer_phone' => ['required', 'regex:/^[6-9][0-9]{9}$/'],
            'shipping_address' => ['required', 'string', 'max:1000'],
            'pincode' => ['required', 'regex:/^[1-9][0-9]{5}$/'],
            'payment_method' => ['required', 'in:upi,card,net_banking,wallet,cod'],
            'accept_terms' => ['accepted'],
        ];

        if ($summary['hasRx']) {
            $rules['prescription'] = ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:8192'];
        } else {
            $rules['prescription'] = ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:8192'];
        }

        $validated = $request->validate($rules);
        $prescriptionPath = null;

        if ($request->hasFile('prescription')) {
            $prescriptionPath = $request->file('prescription')->store('prescriptions', 'local');
            if (! is_string($prescriptionPath)) {
                throw new RuntimeException('The prescription could not be stored securely. Please retry.');
            }
        }

        try {
            $order = DB::transaction(function () use ($validated, $prescriptionPath): Order {
                $cart = session('cart', []);
                $products = [];
                $mrpTotalCents = 0;
                $subtotalCents = 0;

                foreach ($cart as $productId => $quantity) {
                    $product = Product::query()
                        ->whereKey($productId)
                        ->where('active', true)
                        ->lockForUpdate()
                        ->firstOrFail();
                    $quantity = max(1, (int) $quantity);

                    if ($quantity > $product->stock) {
                        throw ValidationException::withMessages([
                            'cart' => "{$product->name} no longer has the requested quantity available.",
                        ]);
                    }

                    $unitPriceCents = $this->toCents($product->price);
                    $unitMrpCents = $this->toCents($product->mrp ?? $product->price);
                    $subtotalCents += $unitPriceCents * $quantity;
                    $mrpTotalCents += $unitMrpCents * $quantity;
                    $products[] = [$product, $quantity, $unitPriceCents];
                }

                $couponApplied = session('coupon') === config('store.coupon_code');
                $couponDiscountCents = $couponApplied
                    ? (int) round($subtotalCents * config('store.coupon_percent') / 100)
                    : 0;
                $deliveryFeeCents = $subtotalCents >= config('store.free_delivery_threshold') * 100
                    ? 0
                    : config('store.delivery_fee') * 100;
                $totalCents = max(0, $subtotalCents - $couponDiscountCents + $deliveryFeeCents);
                $discountCents = max(0, $mrpTotalCents - $subtotalCents + $couponDiscountCents);
                $hasRx = collect($products)->contains(fn (array $line) => $line[0]->rx_required);

                $order = Order::query()->create([
                    'order_number' => strtoupper(Str::random(18)),
                    'customer_name' => $validated['customer_name'],
                    'customer_email' => $validated['customer_email'],
                    'customer_phone' => $validated['customer_phone'],
                    'shipping_address' => $validated['shipping_address'],
                    'pincode' => $validated['pincode'],
                    'payment_method' => $validated['payment_method'],
                    'mrp_total' => $this->fromCents($mrpTotalCents),
                    'discount' => $this->fromCents($discountCents),
                    'delivery_fee' => $this->fromCents($deliveryFeeCents),
                    'coupon_code' => $couponApplied ? config('store.coupon_code') : null,
                    'total' => $this->fromCents($totalCents),
                    'prescription_path' => $prescriptionPath,
                    'status' => $hasRx ? 'pending_pharmacist_review' : 'demo_order_pending',
                ]);

                foreach ($products as [$product, $quantity, $unitPriceCents]) {
                    $order->items()->create([
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'quantity' => $quantity,
                        'unit_price' => $this->fromCents($unitPriceCents),
                        'line_total' => $this->fromCents($unitPriceCents * $quantity),
                    ]);
                    $product->decrement('stock', $quantity);
                }

                return $order;
            });
        } catch (Throwable $exception) {
            if ($prescriptionPath !== null) {
                Storage::disk('local')->delete($prescriptionPath);
            }

            throw $exception;
        }

        $request->session()->forget(['cart', 'coupon']);

        return redirect()->route('orders.show', $order->order_number)
            ->with('status', 'Demo order recorded. Payment and fulfilment are not connected.');
    }

    public function showOrder(string $orderNumber): View
    {
        $order = Order::query()
            ->with('items')
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        return view('order', ['order' => $order]);
    }

    public function subscribe(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'consent' => ['accepted'],
        ]);

        NewsletterSubscriber::query()->firstOrCreate(
            ['email' => strtolower($validated['email'])],
            ['consented_at' => now()],
        );

        return back()->with('status', 'You are subscribed to the sample newsletter list.');
    }

    public function login(): View
    {
        return view('login');
    }

    public function requestOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'phone' => ['required', 'regex:/^[6-9][0-9]{9}$/'],
        ]);

        return back()->with('otp_notice', 'OTP sign-in is not enabled. Connect an approved SMS provider before accepting customer accounts.');
    }

    public function account(): View
    {
        return view('account');
    }

    public function articles(): View
    {
        return view('articles');
    }

    public function policy(string $policy, PageContent $pageContent): View
    {
        $definition = config("cms.pages.{$policy}");
        abort_unless(is_array($definition), 404);

        $fields = $definition['sections']['policy']['fields'];
        $sections = collect(['section_one', 'section_two', 'section_three'])
            ->map(fn (string $field) => $pageContent->value($policy, 'policy', $field, $fields[$field]['default'] ?? ''))
            ->filter(fn ($content) => is_string($content) && trim($content) !== '')
            ->values()
            ->all();

        return view('policy', [
            'title' => $pageContent->value($policy, 'policy', 'title', $fields['title']['default']),
            'intro' => $pageContent->value($policy, 'policy', 'intro', $fields['intro']['default']),
            'sections' => $sections,
        ]);
    }

    private function cartSummary(): array
    {
        $cart = session('cart', []);
        $products = Product::query()
            ->where('active', true)
            ->whereIn('id', array_keys($cart))
            ->get();

        $items = $products->map(function (Product $product) use ($cart): array {
            $quantity = max(0, (int) ($cart[$product->id] ?? 0));
            $priceCents = $this->toCents($product->price);
            $mrpCents = $this->toCents($product->mrp ?? $product->price);

            return [
                'product' => $product,
                'quantity' => $quantity,
                'line_total' => $priceCents * $quantity,
                'line_mrp' => $mrpCents * $quantity,
            ];
        })->filter(fn (array $item) => $item['quantity'] > 0)->values();

        $subtotal = (int) $items->sum('line_total');
        $mrpSubtotal = (int) $items->sum('line_mrp');
        $couponApplied = session('coupon') === config('store.coupon_code');
        $couponDiscount = $couponApplied
            ? (int) round($subtotal * config('store.coupon_percent') / 100)
            : 0;
        $deliveryFee = $subtotal >= config('store.free_delivery_threshold') * 100
            ? 0
            : config('store.delivery_fee') * 100;

        return [
            'items' => $items,
            'subtotal' => $subtotal,
            'mrpSubtotal' => $mrpSubtotal,
            'couponDiscount' => $couponDiscount,
            'discount' => max(0, $mrpSubtotal - $subtotal + $couponDiscount),
            'deliveryFee' => $deliveryFee,
            'total' => max(0, $subtotal - $couponDiscount + $deliveryFee),
            'couponApplied' => $couponApplied,
            'hasRx' => $items->contains(fn (array $item) => $item['product']->rx_required),
        ];
    }

    private function toCents(string|float|int|null $amount): int
    {
        return (int) round((float) $amount * 100);
    }

    private function fromCents(int $amount): string
    {
        return number_format($amount / 100, 2, '.', '');
    }
}
