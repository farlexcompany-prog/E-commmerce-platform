<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StoreController extends Controller
{
    public function index(): View
    {
        return view('store', [
            'products' => Product::query()
                ->where('active', true)
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function cart(): View
    {
        $cart = session('cart', []);
        $products = Product::query()->whereIn('id', array_keys($cart))->get();
        $items = $products->map(fn (Product $product) => [
            'product' => $product,
            'quantity' => (int) $cart[$product->id],
            'line_total' => (int) round((float) $product->price * 100) * (int) $cart[$product->id],
        ]);

        return view('cart', [
            'items' => $items,
            'total' => $items->sum('line_total'),
        ]);
    }

    public function addToCart(Request $request, Product $product): RedirectResponse
    {
        abort_unless($product->active, 404);

        $validated = $request->validate([
            'quantity' => ['sometimes', 'integer', 'min:1', 'max:99'],
        ]);

        $cart = session('cart', []);
        $cart[$product->id] = min(99, (int) ($cart[$product->id] ?? 0) + (int) ($validated['quantity'] ?? 1));
        session(['cart' => $cart]);

        return back()->with('status', 'Added to your cart.');
    }

    public function updateCart(Request $request, Product $product): RedirectResponse
    {
        abort_unless($product->active, 404);

        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
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

    public function checkout(): View|RedirectResponse
    {
        if (empty(session('cart', []))) {
            return redirect()->route('cart.index')->with('status', 'Your cart is empty.');
        }

        return view('checkout', ['total' => $this->cartTotal()]);
    }

    public function placeOrder(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:120'],
            'customer_email' => ['required', 'email', 'max:255'],
            'shipping_address' => ['required', 'string', 'max:1000'],
        ]);

        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('status', 'Your cart is empty.');
        }

        $order = DB::transaction(function () use ($cart, $validated): Order {
            $order = Order::query()->create([
                'order_number' => strtoupper(\Illuminate\Support\Str::random(16)),
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'shipping_address' => $validated['shipping_address'],
                'total' => '0.00',
                'status' => 'pending',
            ]);
            $totalCents = 0;

            foreach ($cart as $productId => $quantity) {
                $product = Product::query()
                    ->whereKey($productId)
                    ->where('active', true)
                    ->lockForUpdate()
                    ->firstOrFail();
                $quantity = max(1, min(99, (int) $quantity));
                $unitPriceCents = (int) round((float) $product->price * 100);
                $lineTotalCents = $unitPriceCents * $quantity;
                $totalCents += $lineTotalCents;

                $order->items()->create([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $quantity,
                    'unit_price' => number_format($unitPriceCents / 100, 2, '.', ''),
                    'line_total' => number_format($lineTotalCents / 100, 2, '.', ''),
                ]);
            }

            $order->update([
                'total' => number_format($totalCents / 100, 2, '.', ''),
            ]);

            return $order;
        });

        $request->session()->forget('cart');

        return redirect()->route('orders.show', $order->order_number)
            ->with('status', 'Order placed successfully.');
    }

    public function showOrder(string $orderNumber): View
    {
        $order = Order::query()
            ->with('items')
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        return view('order', ['order' => $order]);
    }

    private function cartTotal(): int
    {
        $cart = session('cart', []);

        return Product::query()
            ->whereIn('id', array_keys($cart))
            ->get()
            ->sum(fn (Product $product) => (int) round((float) $product->price * 100) * (int) $cart[$product->id]);
    }
}
