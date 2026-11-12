@extends('layout')

@section('title', $pageContent->value('cart', 'heading', 'title', 'Your cart').' | '.config('store.name'))

@section('content')
    <div class="container page-shell">
        <nav class="breadcrumbs" aria-label="Breadcrumb"><a href="{{ route('store') }}">Home</a><span aria-hidden="true">/</span><span>Your cart</span></nav>
        <header class="page-heading"><p class="eyebrow">{{ $pageContent->value('cart', 'heading', 'eyebrow', 'Review your selection') }}</p><h1>{{ $pageContent->value('cart', 'heading', 'title', 'Your cart') }}</h1></header>

        @if ($items->isEmpty())
            <section class="empty-state">
                <h2>{{ $pageContent->value('cart', 'empty', 'title', 'Your cart is waiting for you.') }}</h2>
                <p>{{ $pageContent->value('cart', 'empty', 'description', 'Browse the demo catalogue and add an item to continue.') }}</p>
                <a class="button" href="{{ $pageContent->value('cart', 'empty', 'button_url', '/shop') }}">{{ $pageContent->value('cart', 'empty', 'button_text', 'Browse products') }}</a>
            </section>
        @else
            @if ($hasRx)
                <div class="notice notice--error" role="note"><strong>Prescription required.</strong> Checkout is blocked for Rx-labelled products until a valid prescription is uploaded.</div>
            @endif
            <div class="cart-layout">
                <div>
                    <div class="cart-list">
                        @foreach ($items as $item)
                            @php($product = $item['product'])
                            <article class="cart-line">
                                <a href="{{ route('products.show', $product) }}" aria-label="View {{ $product->name }}"><x-product-art :product="$product" size="small" /></a>
                                <div>
                                    <p class="product-eyebrow">{{ $product->category }} · {{ $product->dosage_form }}</p>
                                    <h2><a href="{{ route('products.show', $product) }}">{{ $product->name }}</a></h2>
                                    <p>{{ $product->pack_size }} · <span class="rx-badge {{ $product->rx_required ? 'rx-badge--required' : '' }}">{{ $product->rx_required ? 'Rx required' : 'OTC' }}</span></p>
                                    <div class="cart-line__actions">
                                        <form method="POST" action="{{ route('cart.update', $product) }}" class="cart-line__actions">
                                            @csrf
                                            @method('PATCH')
                                            <label class="sr-only" for="quantity-{{ $product->id }}">Quantity</label>
                                            <input id="quantity-{{ $product->id }}" name="quantity" type="number" value="{{ $item['quantity'] }}" min="1" max="{{ min(99, $product->stock) }}">
                                            <button class="link-button" type="submit">Update</button>
                                        </form>
                                        <form method="POST" action="{{ route('cart.destroy', $product) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button class="link-button" type="submit">Remove</button>
                                        </form>
                                    </div>
                                </div>
                                <div class="cart-line__price">
                                    <div><strong>{{ config('store.currency_symbol') }}{{ number_format($item['line_total'] / 100, 2) }}</strong><del>{{ config('store.currency_symbol') }}{{ number_format($item['line_mrp'] / 100, 2) }}</del></div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                    <div class="cart-actions">
                        <a class="button button--outline" href="{{ route('shop') }}">Continue shopping</a>
                        <a class="text-link" href="{{ route('policies.show', 'privacy') }}">How prescription uploads are handled →</a>
                    </div>
                </div>

                <aside class="panel summary-panel" aria-label="Price summary">
                    <h2>{{ $pageContent->value('cart', 'summary', 'title', 'Price details') }}</h2>
                    <div class="summary-line"><span>MRP total</span><span>{{ config('store.currency_symbol') }}{{ number_format($mrpSubtotal / 100, 2) }}</span></div>
                    <div class="summary-line"><span>Product savings</span><span>− {{ config('store.currency_symbol') }}{{ number_format(($mrpSubtotal - $subtotal) / 100, 2) }}</span></div>
                    @if ($couponApplied)
                        <div class="coupon-applied"><span>{{ config('store.coupon_code') }} applied</span>
                            <form method="POST" action="{{ route('coupon.destroy') }}">@csrf @method('DELETE')<button class="link-button" type="submit">Remove</button></form>
                        </div>
                        <div class="summary-line"><span>Offer savings</span><span>− {{ config('store.currency_symbol') }}{{ number_format($couponDiscount / 100, 2) }}</span></div>
                    @else
                        <form class="coupon-form" method="POST" action="{{ route('coupon.apply') }}">
                            @csrf
                            <label class="sr-only" for="coupon-code">Offer code</label>
                            <input id="coupon-code" name="coupon_code" placeholder="Enter offer code" maxlength="30">
                            <button type="submit">Apply</button>
                        </form>
                    @endif
                    <div class="summary-line"><span>Delivery</span><span>{{ $deliveryFee === 0 ? 'FREE' : config('store.currency_symbol').number_format($deliveryFee / 100, 2) }}</span></div>
                    <div class="summary-line summary-line--total"><span>Estimated total</span><span>{{ config('store.currency_symbol') }}{{ number_format($total / 100, 2) }}</span></div>
                    <p class="summary-note">Sample pricing only. Final tax, delivery, discount and order terms must be configured before launch.</p>
                    <a class="button button--full" href="{{ route('checkout') }}">{{ $pageContent->value('cart', 'summary', 'checkout_button', 'Continue to checkout') }}</a>
                    <p class="summary-note">Payment options are displayed for demo only; no payment is collected.</p>
                </aside>
            </div>
        @endif
    </div>
@endsection
