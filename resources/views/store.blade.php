@extends('layout')

@section('title', config('store.name').' | Pharmacy & health essentials')
@section('meta_description', config('store.tagline').' Browse a demo health and wellness catalogue.')

@section('content')
    <section class="hero-section">
        <div class="container">
            <div class="hero-carousel" data-carousel aria-label="Featured offers">
                @foreach ($banners as $index => $banner)
                    <article class="hero-slide hero-slide--{{ $banner['tone'] }} {{ $index === 0 ? 'is-active' : '' }}" data-slide aria-hidden="{{ $index === 0 ? 'false' : 'true' }}">
                        <div class="hero-copy">
                            <p class="eyebrow">{{ $banner['eyebrow'] }}</p>
                            <h1>{{ $banner['title'] }}</h1>
                            <p>{{ $banner['copy'] }}</p>
                            <a class="button" href="{{ $banner['url'] }}">{{ $banner['cta'] }} <span aria-hidden="true">→</span></a>
                            <small class="hero-note">Sample storefront · verify all product and offer details before launch</small>
                        </div>
                        <div class="hero-art" aria-hidden="true">
                            <span class="hero-orbit hero-orbit--one"></span>
                            <span class="hero-orbit hero-orbit--two"></span>
                            <span class="hero-cross">+</span>
                            <div class="hero-pack hero-pack--back"><span>DAILY</span><strong>CARE</strong></div>
                            <div class="hero-pack hero-pack--front"><span>{{ config('store.name') }}</span><strong>HEALTH<br>ESSENTIALS</strong><small>DEMO PACK</small></div>
                        </div>
                    </article>
                @endforeach
                <div class="carousel-controls">
                    <button type="button" class="carousel-arrow" data-carousel-prev aria-label="Previous banner">‹</button>
                    <div class="carousel-dots" aria-label="Choose banner">
                        @foreach ($banners as $index => $banner)
                            <button type="button" data-carousel-dot="{{ $index }}" aria-label="Show banner {{ $index + 1 }}" aria-current="{{ $index === 0 ? 'true' : 'false' }}"></button>
                        @endforeach
                    </div>
                    <button type="button" class="carousel-arrow" data-carousel-next aria-label="Next banner">›</button>
                </div>
            </div>
        </div>
    </section>

    <section class="section container">
        <div class="section-heading">
            <div><p class="eyebrow">Explore the catalogue</p><h2>Shop by category</h2></div>
            <a class="text-link" href="{{ route('shop') }}">View all products →</a>
        </div>
        <div class="category-scroller">
            @foreach ($categories as $category)
                <a class="category-tile" href="{{ route('shop', ['category' => $category['query']]) }}">
                    <span class="category-icon" aria-hidden="true">{{ $category['icon'] }}</span>
                    <strong>{{ $category['name'] }}</strong>
                    <span class="tile-arrow" aria-hidden="true">→</span>
                </a>
            @endforeach
        </div>
    </section>

    <section class="section section--soft">
        <div class="container">
            <div class="section-heading">
                <div><p class="eyebrow">Browse with care</p><h2>Shop by health concern</h2></div>
                <p class="section-subtitle">Categories are for browsing only—not a diagnosis or treatment recommendation.</p>
            </div>
            <div class="concern-grid">
                @foreach ($concerns as $concern)
                    <a class="concern-tile" href="{{ route('shop', ['category' => $concern['category']]) }}">
                        <span aria-hidden="true">{{ $concern['icon'] }}</span><strong>{{ $concern['name'] }}</strong>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section container">
        <div class="section-heading">
            <div><p class="eyebrow">A starting point</p><h2>Featured health essentials</h2></div>
            <a class="text-link" href="{{ route('shop') }}">Shop the full range →</a>
        </div>
        <div class="product-scroller">
            @forelse ($featuredProducts as $product)
                <x-product-card :product="$product" />
            @empty
                <p class="muted">Seed the demo catalogue to display products.</p>
            @endforelse
        </div>
    </section>

    <section class="section container promo-grid">
        <a class="promo-card promo-card--mint" href="{{ route('shop', ['category' => 'Vitamins & Supplements']) }}">
            <span class="eyebrow">Sample promotion</span>
            <strong>Supplement offers<br>go here.</strong>
            <span>Configure approved discounts and terms before publishing <span aria-hidden="true">→</span></span>
        </a>
        <a class="promo-card promo-card--cream" href="{{ route('shop') }}">
            <span class="eyebrow">Explore a bundle</span>
            <strong>Thoughtful picks,<br>together.</strong>
            <span>Bundle pricing is not enabled in this demo <span aria-hidden="true">→</span></span>
        </a>
        <a class="promo-card promo-card--lavender" href="{{ route('shop', ['rx' => 'rx']) }}">
            <span class="eyebrow">Prescription care</span>
            <strong>Rx items are<br>clearly labelled.</strong>
            <span>Upload a prescription during checkout <span aria-hidden="true">→</span></span>
        </a>
    </section>

    <section class="section section--navy">
        <div class="container">
            <div class="section-heading section-heading--light">
                <div><p class="eyebrow">Built with clarity</p><h2>What this storefront is designed to do</h2></div>
                <p class="section-subtitle">Operational trust claims must be verified before your site goes live.</p>
            </div>
            <div class="trust-grid">
                <article><span aria-hidden="true">✓</span><h3>Separate Rx and OTC</h3><p>Prescription items are labelled and guarded at checkout.</p></article>
                <article><span aria-hidden="true">⌑</span><h3>Private upload path</h3><p>Demo prescriptions are stored outside the public web directory.</p></article>
                <article><span aria-hidden="true">₹</span><h3>Clear price breakdown</h3><p>MRP, discounts and delivery are shown separately.</p></article>
                <article><span aria-hidden="true">i</span><h3>Careful product copy</h3><p>Placeholder details avoid unsupported medical claims.</p></article>
            </div>
        </div>
    </section>

    <section class="section container prescription-feature" id="prescription">
        <div class="prescription-copy">
            <p class="eyebrow">For products marked Rx</p>
            <h2>Prescription upload, in three steps.</h2>
            <p>Add the requested product to your cart, upload a valid prescription at checkout, then wait for the required pharmacy review.</p>
            <a class="button" href="{{ route('shop', ['rx' => 'rx']) }}">Browse Rx-labelled products</a>
            <p class="fine-print">No prescription image is made publicly accessible. Real review workflows and retention controls must be configured before launch.</p>
        </div>
        <ol class="steps-list">
            <li><span>01</span><div><strong>Upload</strong><p>PDF, JPG or PNG · up to 8 MB</p></div></li>
            <li><span>02</span><div><strong>Verification pending</strong><p>A qualified pharmacy professional must review it.</p></div></li>
            <li><span>03</span><div><strong>Fulfilment</strong><p>Only after verification and payment integration.</p></div></li>
        </ol>
    </section>

    <section class="section section--soft">
        <div class="container">
            <div class="section-heading">
                <div><p class="eyebrow">Partner brands</p><h2>Featured brands</h2></div>
                <p class="section-subtitle">Replace demo labels with approved brand assets.</p>
            </div>
            <div class="brand-strip" aria-label="Brand logo placeholders">
                <span>BRAND 01</span><span>BRAND 02</span><span>BRAND 03</span><span>BRAND 04</span><span>YOUR BRAND</span>
            </div>
        </div>
    </section>

    <section class="section container">
        <div class="testimonial-panel">
            <span class="testimonial-mark" aria-hidden="true">“</span>
            <div><p class="eyebrow">Verified customer reviews</p><h2>Real customer stories will appear here.</h2><p>We don't publish sample testimonials or ratings as if they were genuine. Add verified reviews after your review process is ready.</p></div>
            <a class="text-link" href="{{ route('shop') }}">Explore products →</a>
        </div>
    </section>

    <section class="section section--soft">
        <div class="container">
            <div class="section-heading">
                <div><p class="eyebrow">Helpful reading</p><h2>Health & product guides</h2></div>
                <a class="text-link" href="{{ route('articles') }}">More articles →</a>
            </div>
            <div class="article-grid">
                <a class="article-card" href="{{ route('articles') }}#product-labels"><span class="article-art article-art--mint" aria-hidden="true">01</span><small>Product information</small><h3>How to read a product label</h3><span>Read guide →</span></a>
                <a class="article-card" href="{{ route('articles') }}#storage"><span class="article-art article-art--cream" aria-hidden="true">02</span><small>Everyday care</small><h3>Keeping products in original packaging</h3><span>Read guide →</span></a>
                <a class="article-card" href="{{ route('articles') }}#pharmacist"><span class="article-art article-art--lavender" aria-hidden="true">03</span><small>Pharmacy support</small><h3>Questions to ask your pharmacist</h3><span>Read guide →</span></a>
            </div>
        </div>
    </section>

    <section class="section container app-banner">
        <div><p class="eyebrow">Coming soon</p><h2>Care that fits your day.</h2><p>App downloads and refill reminders will be available after the mobile service is configured.</p></div>
        <div class="app-badges"><span class="store-badge">Google Play <small>Coming soon</small></span><span class="store-badge">App Store <small>Coming soon</small></span></div>
    </section>

    <section class="newsletter-section">
        <div class="container newsletter-inner">
            <div><p class="eyebrow">A thoughtful inbox</p><h2>Notes for your wellness routine.</h2><p>Subscribe for product and store updates. No medical advice or treatment claims.</p></div>
            <form method="POST" action="{{ route('newsletter.subscribe') }}" class="newsletter-form">
                @csrf
                <label class="sr-only" for="newsletter-email">Email address</label>
                <input id="newsletter-email" name="email" type="email" placeholder="Your email address" required maxlength="255">
                <label class="consent-label"><input type="checkbox" name="consent" value="1" required> I agree to receive store updates and can unsubscribe later.</label>
                <button class="button" type="submit">Subscribe</button>
            </form>
        </div>
    </section>
@endsection
