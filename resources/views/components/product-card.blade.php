@props(['product'])

@php
    $mrp = (float) ($product->mrp ?? $product->price);
    $price = (float) $product->price;
    $discountPercent = $mrp > 0 ? (int) floor((($mrp - $price) / $mrp) * 100) : 0;
    $inWishlist = isset(session('wishlist', [])[$product->id]);
@endphp

<article class="product-card">
    <div class="product-card__media">
        <a href="{{ route('products.show', $product) }}" aria-label="View {{ $product->name }}">
            <x-product-art :product="$product" />
        </a>
        @if ($discountPercent > 0)
            <span class="discount-pill">{{ $discountPercent }}% off</span>
        @endif
        <form class="wishlist-form" method="POST" action="{{ route('wishlist.toggle', $product) }}">
            @csrf
            <button type="submit" class="icon-button {{ $inWishlist ? 'is-saved' : '' }}" aria-label="{{ $inWishlist ? 'Remove from' : 'Add to' }} wishlist">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20.8 8.8c0 5.2-8.8 11-8.8 11s-8.8-5.8-8.8-11a4.8 4.8 0 0 1 8.8-2.6 4.8 4.8 0 0 1 8.8 2.6Z"/></svg>
            </button>
        </form>
    </div>
    <div class="product-card__body">
        <p class="product-eyebrow">{{ $product->category }} <span aria-hidden="true">·</span> {{ $product->dosage_form }}</p>
        <h3><a href="{{ route('products.show', $product) }}">{{ $product->name }}</a></h3>
        <p class="product-meta">{{ $product->brand }} <span aria-hidden="true">·</span> {{ $product->pack_size }}</p>
        <div class="product-flags">
            <span class="rx-badge {{ $product->rx_required ? 'rx-badge--required' : '' }}">{{ $product->rx_required ? 'Rx required' : 'OTC' }}</span>
            <span class="review-empty">No verified reviews</span>
        </div>
        <div class="price-row">
            <strong>{{ config('store.currency_symbol') }}{{ number_format($price, 2) }}</strong>
            @if ($mrp > $price)
                <del>{{ config('store.currency_symbol') }}{{ number_format($mrp, 2) }}</del>
            @endif
        </div>
        <form method="POST" action="{{ route('cart.store', $product) }}">
            @csrf
            <button type="submit" class="button button--small button--full">Add to cart</button>
        </form>
        <a class="text-link product-details-link" href="{{ route('products.show', $product) }}">View product details <span aria-hidden="true">→</span></a>
    </div>
</article>
