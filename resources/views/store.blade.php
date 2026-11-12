@extends('layout')

@section('title', config('store.name').' | Pharmacy & health essentials')
@section('meta_description', $pageContent->value('home', 'banner_one', 'copy', config('store.tagline').' Browse a demo health and wellness catalogue.'))
@section('page-layout-class', 'cms-sortable-page')

@section('content')
    @if (count($banners))
    <section class="hero-section" style="order: {{ $banners[0]['order'] }}">
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
                            @if ($banner['image'])
                                <img class="hero-banner-image" src="{{ $banner['image'] }}" alt="" loading="lazy">
                            @else
                                <span class="hero-orbit hero-orbit--one"></span>
                                <span class="hero-orbit hero-orbit--two"></span>
                                <span class="hero-cross">+</span>
                                <div class="hero-pack hero-pack--back"><span>DAILY</span><strong>CARE</strong></div>
                                <div class="hero-pack hero-pack--front"><span>{{ config('store.name') }}</span><strong>HEALTH<br>ESSENTIALS</strong><small>DEMO PACK</small></div>
                            @endif
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
    @endif

    @if ($pageContent->enabled('home', 'categories'))
    <section class="section container" style="order: {{ $pageContent->order('home', 'categories', 40) }}">
        <div class="section-heading">
            <div><p class="eyebrow">{{ $pageContent->value('home', 'categories', 'eyebrow', 'Explore the catalogue') }}</p><h2>{{ $pageContent->value('home', 'categories', 'title', 'Shop by category') }}</h2></div>
            <a class="text-link" href="{{ route('shop') }}">{{ $pageContent->value('home', 'categories', 'link_text', 'View all products →') }}</a>
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
    @endif

    @if ($pageContent->enabled('home', 'concerns'))
    <section class="section section--soft" style="order: {{ $pageContent->order('home', 'concerns', 50) }}">
        <div class="container">
            <div class="section-heading">
                <div><p class="eyebrow">{{ $pageContent->value('home', 'concerns', 'eyebrow', 'Browse with care') }}</p><h2>{{ $pageContent->value('home', 'concerns', 'title', 'Shop by health concern') }}</h2></div>
                <p class="section-subtitle">{{ $pageContent->value('home', 'concerns', 'description', 'Categories are for browsing only—not a diagnosis or treatment recommendation.') }}</p>
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
    @endif

    @if ($pageContent->enabled('home', 'featured'))
    <section class="section container" style="order: {{ $pageContent->order('home', 'featured', 60) }}">
        <div class="section-heading">
            <div><p class="eyebrow">{{ $pageContent->value('home', 'featured', 'eyebrow', 'A starting point') }}</p><h2>{{ $pageContent->value('home', 'featured', 'title', 'Featured health essentials') }}</h2></div>
            <a class="text-link" href="{{ route('shop') }}">{{ $pageContent->value('home', 'featured', 'link_text', 'Shop the full range →') }}</a>
        </div>
        <div class="product-scroller">
            @forelse ($featuredProducts as $product)
                <x-product-card :product="$product" />
            @empty
                <p class="muted">Seed the demo catalogue to display products.</p>
            @endforelse
        </div>
    </section>
    @endif

    @if ($pageContent->enabled('home', 'promotions'))
    <section class="section container promo-grid" style="order: {{ $pageContent->order('home', 'promotions', 70) }}">
        @foreach (['one' => 'mint', 'two' => 'cream', 'three' => 'lavender'] as $card => $tone)
            <a class="promo-card promo-card--{{ $tone }}" href="{{ $pageContent->value('home', 'promotions', $card.'_url', '/shop') }}">
                <span class="eyebrow">{{ $pageContent->value('home', 'promotions', $card.'_eyebrow', '') }}</span>
                <strong>{{ $pageContent->value('home', 'promotions', $card.'_title', '') }}</strong>
                <span>{{ $pageContent->value('home', 'promotions', $card.'_copy', '') }}</span>
            </a>
        @endforeach
    </section>
    @endif

    @if ($pageContent->enabled('home', 'trust'))
    <section class="section section--navy" style="order: {{ $pageContent->order('home', 'trust', 80) }}">
        <div class="container">
            <div class="section-heading section-heading--light">
                <div><p class="eyebrow">{{ $pageContent->value('home', 'trust', 'eyebrow', 'Built with clarity') }}</p><h2>{{ $pageContent->value('home', 'trust', 'title', 'What this storefront is designed to do') }}</h2></div>
                <p class="section-subtitle">{{ $pageContent->value('home', 'trust', 'description', 'Operational trust claims must be verified before your site goes live.') }}</p>
            </div>
            <div class="trust-grid">
                <article><span aria-hidden="true">✓</span><h3>Separate Rx and OTC</h3><p>Prescription items are labelled and guarded at checkout.</p></article>
                <article><span aria-hidden="true">⌑</span><h3>Private upload path</h3><p>Demo prescriptions are stored outside the public web directory.</p></article>
                <article><span aria-hidden="true">₹</span><h3>Clear price breakdown</h3><p>MRP, discounts and delivery are shown separately.</p></article>
                <article><span aria-hidden="true">i</span><h3>Careful product copy</h3><p>Placeholder details avoid unsupported medical claims.</p></article>
            </div>
        </div>
    </section>
    @endif

    @if ($pageContent->enabled('home', 'prescription'))
    <section class="section container prescription-feature" id="prescription" style="order: {{ $pageContent->order('home', 'prescription', 90) }}">
        <div class="prescription-copy">
            <p class="eyebrow">{{ $pageContent->value('home', 'prescription', 'eyebrow', 'For products marked Rx') }}</p>
            <h2>{{ $pageContent->value('home', 'prescription', 'title', 'Prescription upload, in three steps.') }}</h2>
            <p>{{ $pageContent->value('home', 'prescription', 'copy', 'Add the requested product to your cart, upload a valid prescription at checkout, then wait for the required pharmacy review.') }}</p>
            <a class="button" href="{{ $pageContent->value('home', 'prescription', 'button_url', '/shop?rx=rx') }}">{{ $pageContent->value('home', 'prescription', 'button_text', 'Browse Rx-labelled products') }}</a>
            <p class="fine-print">No prescription image is made publicly accessible. Real review workflows and retention controls must be configured before launch.</p>
        </div>
        <ol class="steps-list">
            <li><span>01</span><div><strong>Upload</strong><p>PDF, JPG or PNG · up to 8 MB</p></div></li>
            <li><span>02</span><div><strong>Verification pending</strong><p>A qualified pharmacy professional must review it.</p></div></li>
            <li><span>03</span><div><strong>Fulfilment</strong><p>Only after verification and payment integration.</p></div></li>
        </ol>
    </section>
    @endif

    @if ($pageContent->enabled('home', 'brands'))
    <section class="section section--soft" style="order: {{ $pageContent->order('home', 'brands', 100) }}">
        <div class="container">
            <div class="section-heading">
                <div><p class="eyebrow">{{ $pageContent->value('home', 'brands', 'eyebrow', 'Partner brands') }}</p><h2>{{ $pageContent->value('home', 'brands', 'title', 'Featured brands') }}</h2></div>
                <p class="section-subtitle">{{ $pageContent->value('home', 'brands', 'description', 'Replace demo labels with approved brand assets.') }}</p>
            </div>
            <div class="brand-strip" aria-label="Brand logo placeholders">
                <span>BRAND 01</span><span>BRAND 02</span><span>BRAND 03</span><span>BRAND 04</span><span>YOUR BRAND</span>
            </div>
        </div>
    </section>
    @endif

    @if ($pageContent->enabled('home', 'testimonials'))
    <section class="section container" style="order: {{ $pageContent->order('home', 'testimonials', 110) }}">
        <div class="testimonial-panel">
            <span class="testimonial-mark" aria-hidden="true">“</span>
            <div><p class="eyebrow">{{ $pageContent->value('home', 'testimonials', 'eyebrow', 'Verified customer reviews') }}</p><h2>{{ $pageContent->value('home', 'testimonials', 'title', 'Real customer stories will appear here.') }}</h2><p>{{ $pageContent->value('home', 'testimonials', 'copy', "We don't publish sample testimonials or ratings as if they were genuine. Add verified reviews after your review process is ready.") }}</p></div>
            <a class="text-link" href="{{ route('shop') }}">Explore products →</a>
        </div>
    </section>
    @endif

    @if ($pageContent->enabled('home', 'articles'))
    <section class="section section--soft" style="order: {{ $pageContent->order('home', 'articles', 120) }}">
        <div class="container">
            <div class="section-heading">
                <div><p class="eyebrow">{{ $pageContent->value('home', 'articles', 'eyebrow', 'Helpful reading') }}</p><h2>{{ $pageContent->value('home', 'articles', 'title', 'Health & product guides') }}</h2></div>
                <a class="text-link" href="{{ route('articles') }}">{{ $pageContent->value('home', 'articles', 'link_text', 'More articles →') }}</a>
            </div>
            <div class="article-grid">
                @php
                    $homepageArticles = collect([
                        'article_one' => ['number' => '01', 'tone' => 'mint', 'anchor' => 'product-labels', 'order' => 20],
                        'article_two' => ['number' => '02', 'tone' => 'cream', 'anchor' => 'storage', 'order' => 30],
                        'article_three' => ['number' => '03', 'tone' => 'lavender', 'anchor' => 'pharmacist', 'order' => 40],
                    ])->sortBy(fn ($article, $section) => $pageContent->order('articles', $section, $article['order']));
                @endphp
                @foreach ($homepageArticles as $section => $article)
                    @if ($pageContent->enabled('articles', $section))
                        <a class="article-card" href="{{ route('articles') }}#{{ $article['anchor'] }}">
                            <span class="article-art article-art--{{ $article['tone'] }}" aria-hidden="true">
                                @if ($pageContent->imageUrl('articles', $section, 'image'))
                                    <img src="{{ $pageContent->imageUrl('articles', $section, 'image') }}" alt="" loading="lazy">
                                @else
                                    {{ $article['number'] }}
                                @endif
                            </span>
                            <small>{{ $pageContent->value('articles', $section, 'eyebrow', '') }}</small>
                            <h3>{{ $pageContent->value('articles', $section, 'title', '') }}</h3>
                            <span>Read guide →</span>
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @if ($pageContent->enabled('home', 'app'))
    <section class="section container app-banner" style="order: {{ $pageContent->order('home', 'app', 130) }}">
        <div><p class="eyebrow">{{ $pageContent->value('home', 'app', 'eyebrow', 'Coming soon') }}</p><h2>{{ $pageContent->value('home', 'app', 'title', 'Care that fits your day.') }}</h2><p>{{ $pageContent->value('home', 'app', 'copy', 'App downloads and refill reminders will be available after the mobile service is configured.') }}</p></div>
        <div class="app-badges"><span class="store-badge">Google Play <small>Coming soon</small></span><span class="store-badge">App Store <small>Coming soon</small></span></div>
    </section>
    @endif

    @if ($pageContent->enabled('home', 'newsletter'))
    <section class="newsletter-section" style="order: {{ $pageContent->order('home', 'newsletter', 140) }}">
        <div class="container newsletter-inner">
            <div><p class="eyebrow">{{ $pageContent->value('home', 'newsletter', 'eyebrow', 'A thoughtful inbox') }}</p><h2>{{ $pageContent->value('home', 'newsletter', 'title', 'Notes for your wellness routine.') }}</h2><p>{{ $pageContent->value('home', 'newsletter', 'copy', 'Subscribe for product and store updates. No medical advice or treatment claims.') }}</p></div>
            <form method="POST" action="{{ route('newsletter.subscribe') }}" class="newsletter-form">
                @csrf
                <label class="sr-only" for="newsletter-email">Email address</label>
                <input id="newsletter-email" name="email" type="email" placeholder="Your email address" required maxlength="255">
                <label class="consent-label"><input type="checkbox" name="consent" value="1" required> I agree to receive store updates and can unsubscribe later.</label>
                <button class="button" type="submit">Subscribe</button>
            </form>
        </div>
    </section>
    @endif
@endsection
