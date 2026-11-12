@extends('layout')

@php
    $mrp = (float) ($product->mrp ?? $product->price);
    $price = (float) $product->price;
    $discountPercent = $mrp > 0 ? (int) floor((($mrp - $price) / $mrp) * 100) : 0;
@endphp

@section('title', $product->name.' | '.config('store.name'))
@section('meta_description', $product->description)

@section('content')
    <div class="container page-shell">
        <nav class="breadcrumbs" aria-label="Breadcrumb">
            <a href="{{ route('store') }}">Home</a><span aria-hidden="true">/</span>
            <a href="{{ route('shop', ['category' => $product->category]) }}">{{ $product->category }}</a><span aria-hidden="true">/</span>
            <span>{{ $product->name }}</span>
        </nav>

        <div class="product-detail-layout">
            <div class="product-gallery">
                <div data-gallery-main>
                    <x-product-art :product="$product" size="detail" />
                </div>
                <div class="gallery-thumbnails" aria-label="Demo packaging views">
                    @foreach ([$product->art_style, 'device', 'bottle', 'tablet'] as $index => $style)
                        <button type="button" class="gallery-thumb {{ $index === 0 ? 'is-active' : '' }}" data-gallery-thumb="{{ $style }}" aria-pressed="{{ $index === 0 ? 'true' : 'false' }}" aria-label="Show demo placeholder view {{ $index + 1 }}">
                            <span class="sr-only">Demo view {{ $index + 1 }}</span>
                            <span class="product-art product-art--{{ in_array($style, ['tablet', 'capsule', 'bottle', 'tube', 'vial', 'supplement', 'jar', 'device'], true) ? $style : 'tablet' }} product-art--small" aria-hidden="true"><span class="pack-illustration"><strong>DEMO</strong><span class="pack-rule"></span></span></span>
                        </button>
                    @endforeach
                </div>
            </div>

            <section class="product-detail-info">
                <p class="eyebrow">{{ $product->category }} · {{ $product->dosage_form }}</p>
                <h1>{{ $product->name }}</h1>
                <div class="product-detail-meta">
                    <span>{{ $product->brand }}</span><span aria-hidden="true">·</span>
                    <span>{{ $product->manufacturer }}</span><span aria-hidden="true">·</span>
                    <span>{{ $product->review_count > 0 ? number_format((float) $product->rating, 1).' ★ · '.$product->review_count.' verified reviews' : 'No verified reviews yet' }}</span>
                </div>
                <span class="rx-badge {{ $product->rx_required ? 'rx-badge--required' : '' }}">{{ $product->rx_required ? 'Prescription required' : 'Over the counter (OTC)' }}</span>
                <div class="price-row">
                    <strong>{{ config('store.currency_symbol') }}{{ number_format($price, 2) }}</strong>
                    @if ($mrp > $price)
                        <del>{{ config('store.currency_symbol') }}{{ number_format($mrp, 2) }}</del>
                        <span class="discount-pill">{{ $discountPercent }}% off</span>
                    @endif
                </div>
                <p class="tax-note">Demo price, not for sale · Confirm applicable tax treatment before launch</p>
                <div class="product-info-badges">
                    <span class="info-chip">{{ $product->pack_size }}</span>
                    <span class="info-chip">Demo listing</span>
                    <span class="info-chip">Stock: {{ $product->stock > 0 ? 'Available in demo' : 'Unavailable' }}</span>
                </div>

                <div class="pack-picker" aria-label="Pack size">
                    <strong>Pack size</strong>
                    <span>{{ $product->pack_size }}</span>
                </div>
                <form class="purchase-row" method="POST" action="{{ route('cart.store', $product) }}">
                    @csrf
                    <label class="sr-only" for="product-quantity">Quantity</label>
                    <select id="product-quantity" name="quantity" aria-label="Quantity">
                        @for ($quantity = 1; $quantity <= min(5, $product->stock); $quantity++)
                            <option value="{{ $quantity }}">{{ $quantity }}</option>
                        @endfor
                    </select>
                    <button class="button" type="submit">Add to cart</button>
                    <button class="button button--soft" type="submit" name="go_to_checkout" value="1">Buy now</button>
                </form>
                <form method="POST" action="{{ route('wishlist.toggle', $product) }}">
                    @csrf
                    <button class="button button--outline" type="submit">♡ Add to wishlist</button>
                </form>

                <form class="delivery-check" data-pincode-check>
                    <label class="sr-only" for="product-pincode">Check delivery pincode</label>
                    <input id="product-pincode" inputmode="numeric" pattern="[1-9][0-9]{5}" maxlength="6" placeholder="Enter 6-digit pincode">
                    <button class="button button--outline" type="submit">Check</button>
                    <p data-pincode-message>Delivery availability will be confirmed at checkout; live pincode lookup is not configured.</p>
                </form>

                <p class="detail-description">{{ $product->description }}</p>
                <div class="detail-accordion">
                    @foreach ([
                        'Description' => $product->description,
                        'Composition / salt' => $product->details['composition'] ?? 'Add approved composition information.',
                        'Uses' => $product->details['uses'] ?? 'Add approved label information.',
                        'Dosage & how to use' => $product->details['dosage'] ?? 'Follow directions from a registered medical practitioner.',
                        'Side effects' => $product->details['side_effects'] ?? 'Add reviewed product-label information.',
                        'Warnings & precautions' => $product->details['warnings'] ?? 'Add reviewed safety information before launch.',
                        'Storage' => $product->details['storage'] ?? 'Follow the approved packaging.',
                        'Manufacturer details' => $product->manufacturer,
                        'FAQs' => $product->details['faqs'] ?? 'Add reviewed product-specific FAQs.',
                    ] as $label => $content)
                        <details>
                            <summary>{{ $label }}</summary>
                            <p>{{ $content }}</p>
                        </details>
                    @endforeach
                </div>
                <div class="medical-disclaimer">Medicines should be taken only as directed by a registered medical practitioner. Product information shown here is a demo placeholder and must be replaced with approved label copy.</div>
            </section>
        </div>

        <section class="subsection">
            <div class="section-heading"><div><p class="eyebrow">Explore similar items</p><h2>More in {{ $product->category }}</h2></div><a class="text-link" href="{{ route('shop', ['category' => $product->category]) }}">View category →</a></div>
            <div class="product-grid">
                @forelse ($similarProducts as $similarProduct)
                    <x-product-card :product="$similarProduct" />
                @empty
                    <p class="muted">More demo products will appear here.</p>
                @endforelse
            </div>
        </section>

        <section class="subsection">
            <div class="section-heading"><div><p class="eyebrow">Frequently explored</p><h2>More demo products</h2></div></div>
            <div class="product-scroller">
                @foreach ($frequentlyBought as $relatedProduct)
                    <x-product-card :product="$relatedProduct" />
                @endforeach
            </div>
        </section>

        <section class="subsection" id="reviews">
            <div class="panel">
                <p class="eyebrow">Customer reviews</p>
                <h2>No verified reviews yet</h2>
                <p class="muted">Reviews and ratings will appear after a verified review collection and moderation process is configured.</p>
            </div>
        </section>
    </div>

    <div class="sticky-cart-mobile">
        <strong>{{ config('store.currency_symbol') }}{{ number_format($price, 2) }}</strong>
        <form method="POST" action="{{ route('cart.store', $product) }}">
            @csrf
            <input type="hidden" name="quantity" value="1">
            <button type="submit" class="button button--small">Add to cart</button>
        </form>
    </div>
@endsection
