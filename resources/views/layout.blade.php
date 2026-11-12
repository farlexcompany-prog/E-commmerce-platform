<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ strip_tags($__env->yieldContent('meta_description', config('store.tagline'))) }}">
    <meta name="theme-color" content="#000042">
    <title>{{ strip_tags($__env->yieldContent('title', config('store.name').' | Health essentials')) }}</title>
    <link rel="preload" href="{{ asset('fonts/plus-jakarta-sans-latin-variable.woff2') }}" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="{{ asset('fonts/inter-latin-variable.woff2') }}" as="font" type="font/woff2" crossorigin>
    <link rel="stylesheet" href="{{ asset('css/pharmacy.css') }}">
    <script defer src="{{ asset('js/pharmacy.js') }}"></script>
</head>
<body>
    <div class="announcement">
        <div class="container announcement__inner">
            <span>{{ $pageContent->value('site', 'announcement', 'delivery', 'Free delivery above '.config('store.currency_symbol').config('store.free_delivery_threshold')) }}</span>
            <span class="announcement__divider" aria-hidden="true">•</span>
            <span>{{ $pageContent->value('site', 'announcement', 'prescription', 'Prescription checks for Rx products') }}</span>
            <span class="announcement__divider" aria-hidden="true">•</span>
            <span>{{ $pageContent->value('site', 'announcement', 'coupon', 'Use code '.config('store.coupon_code')) }}</span>
            <a href="{{ route('shop') }}" class="announcement__link">{{ $pageContent->value('site', 'announcement', 'link_text', 'Explore the demo range') }}</a>
        </div>
    </div>

    <header class="site-header">
        <div class="container">
            <div class="header-main">
                <a class="brand-lockup" href="{{ route('store') }}">
                    <img class="brand-logo" src="{{ asset('images/farlex-logo.png') }}" alt="{{ config('store.name') }}" width="145" height="84" decoding="async">
                </a>

                <form class="site-search" method="GET" action="{{ route('shop') }}" role="search">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="10.8" cy="10.8" r="6.8"/><path d="m16 16 5 5"/></svg>
                    <label class="sr-only" for="site-search-input">Search products</label>
                    <input id="site-search-input" name="q" type="search" placeholder="Search medicines, health products, brands" autocomplete="off" list="search-suggestions" data-search-suggestions="{{ route('search.suggestions') }}" value="{{ request('q') }}">
                    <datalist id="search-suggestions"></datalist>
                    <span id="search-feedback" class="sr-only" aria-live="polite"></span>
                    <button type="submit" class="sr-only">Search</button>
                </form>

                <div class="header-actions">
                    <a class="button button--outline prescription-link" href="{{ route('shop', ['rx' => 'rx']) }}">Upload prescription</a>
                    <a class="header-link" href="{{ route('login') }}"><span class="header-icon" aria-hidden="true">♙</span><span>Login</span></a>
                    <a class="header-link header-link--optional" href="{{ route('wishlist.index') }}"><span class="header-icon" aria-hidden="true">♡</span><span>Wishlist <small>({{ count(session('wishlist', [])) }})</small></span></a>
                    <a class="header-link" href="{{ route('cart.index') }}"><span class="header-icon" aria-hidden="true">▱</span><span>Cart <small>({{ array_sum(session('cart', [])) }})</small></span></a>
                    <details class="location-picker">
                        <summary aria-label="Choose delivery pincode"><span aria-hidden="true">⌖</span> Pincode</summary>
                        <div class="location-popover">
                            <strong>Check delivery availability</strong>
                            <label class="sr-only" for="header-pincode">6-digit delivery pincode</label>
                            <input id="header-pincode" inputmode="numeric" pattern="[1-9][0-9]{5}" maxlength="6" placeholder="Enter 6-digit pincode">
                            <p data-pincode-message>Live delivery lookup is not configured.</p>
                        </div>
                    </details>
                </div>
            </div>

            <nav class="primary-nav" aria-label="Main navigation">
                <a href="{{ route('shop') }}">{{ $pageContent->value('site', 'navigation', 'medicines', 'Medicines') }}</a>
                <a href="{{ route('shop', ['category' => 'Healthcare Devices']) }}">{{ $pageContent->value('site', 'navigation', 'devices', 'Healthcare Devices') }}</a>
                <a href="{{ route('shop', ['category' => 'Vitamins & Supplements']) }}">{{ $pageContent->value('site', 'navigation', 'supplements', 'Vitamins & Supplements') }}</a>
                <a href="{{ route('shop', ['category' => 'Diabetes Care']) }}">{{ $pageContent->value('site', 'navigation', 'diabetes', 'Diabetes Care') }}</a>
                <details class="nav-menu">
                    <summary>{{ $pageContent->value('site', 'navigation', 'more', 'More categories') }} <span aria-hidden="true">⌄</span></summary>
                    <div class="mega-menu">
                        <div>
                            <h2>Shop health essentials</h2>
                            <p>Product and category content here is demo data. Replace it with approved catalogue information.</p>
                            <a class="text-link" href="{{ route('shop') }}">Browse all products →</a>
                        </div>
                        <div class="mega-menu__links">
                            @foreach (config('store.categories') as $category)
                                <a href="{{ route('shop', ['category' => $category['query']]) }}">{{ $category['name'] }}</a>
                            @endforeach
                            <a href="{{ route('articles') }}">Health articles</a>
                            <a href="{{ route('shop', ['sort' => 'discount']) }}">Offers</a>
                        </div>
                    </div>
                </details>
                <a class="nav-offer" href="{{ route('shop', ['sort' => 'discount']) }}">{{ $pageContent->value('site', 'navigation', 'offers', 'Offers') }} <span>{{ config('store.coupon_code') }}</span></a>
                <span class="language-note" title="Hindi translations are not configured yet">EN <span aria-hidden="true">·</span> हिंदी soon</span>
            </nav>
        </div>
    </header>

    @if (session('status') || session('otp_notice'))
        <div class="container notice-stack" aria-live="polite">
            @if (session('status')) <div class="notice notice--success">{{ session('status') }}</div> @endif
            @if (session('otp_notice')) <div class="notice">{{ session('otp_notice') }}</div> @endif
        </div>
    @endif

    @if ($errors->any())
        <div class="container notice-stack" role="alert">
            <div class="notice notice--error">
                <strong>Please check the form:</strong>
                <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        </div>
    @endif

    <main id="main-content" class="{{ $__env->yieldContent('page-layout-class') }}">
        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="container">
            <div class="footer-top">
                <div class="footer-brand">
                    <a class="brand-lockup brand-lockup--light" href="{{ route('store') }}">
                        <img class="brand-logo" src="{{ asset('images/farlex-logo.png') }}" alt="{{ config('store.name') }}" width="145" height="84" loading="lazy" decoding="async">
                    </a>
                    <p>{{ $pageContent->value('site', 'footer', 'intro', 'Demo storefront. Product, regulatory, contact and policy details must be reviewed before launch.') }}</p>
                    <div class="social-links" aria-label="Farlex social media profiles">
                        @foreach (config('store.social_links') as $social)
                            <a class="social-link" href="{{ $social['url'] }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $social['name'] }}: {{ $social['label'] }} (opens in a new tab)">
                                @if (!empty($social['icon']))
                                    <img src="{{ asset('images/'.$social['icon']) }}" alt="" width="24" height="24" loading="lazy" decoding="async">
                                @else
                                    <span class="social-link__mark" aria-hidden="true">WA</span>
                                @endif
                                <span class="social-link__copy"><strong>{{ $social['name'] }}</strong><span>{{ $social['label'] }}</span></span>
                            </a>
                        @endforeach
                    </div>
                </div>
                <div class="footer-column">
                    <h2>{{ $pageContent->value('site', 'footer', 'explore_heading', 'Explore') }}</h2>
                    <a href="{{ route('shop') }}">Shop all</a>
                    <a href="{{ route('articles') }}">Health articles</a>
                    <a href="{{ route('wishlist.index') }}">Wishlist</a>
                    <a href="{{ route('account') }}">My account</a>
                </div>
                <div class="footer-column">
                    <h2>{{ $pageContent->value('site', 'footer', 'help_heading', 'Help & policies') }}</h2>
                    <a href="{{ route('policies.show', 'shipping') }}">Shipping policy</a>
                    <a href="{{ route('policies.show', 'refund') }}">Refund policy draft</a>
                    <a href="{{ route('policies.show', 'privacy') }}">Privacy & health data</a>
                    <a href="{{ route('policies.show', 'terms') }}">Terms of service</a>
                </div>
                <div class="footer-column footer-contact" id="support-contact">
                    <h2>{{ $pageContent->value('site', 'footer', 'contact_heading', 'Contact & compliance') }}</h2>
                    <span>{{ config('store.support_email') }}</span>
                    <span>{{ config('store.support_phone') }}</span>
                    <span>Drug licence: {{ config('store.drug_licence') }}</span>
                    <span>Pharmacist registration: {{ config('store.pharmacist_registration') }}</span>
                </div>
            </div>
            <div class="footer-disclaimer">{{ $pageContent->value('site', 'footer', 'disclaimer', 'Medicines should be taken only as directed by a registered medical practitioner. Demo catalogue copy and prices are placeholders; prescription review, customer accounts and payment processing are not live.') }}</div>
            <div class="footer-bottom">
                <span>© {{ date('Y') }} {{ config('store.name') }} · Demo storefront</span>
                <span>UPI · Cards · Net banking · Wallets · COD <small>(payment gateway not connected)</small></span>
            </div>
        </div>
    </footer>

    <a class="support-float" href="#support-contact" aria-label="Find pharmacist and support contact details">?</a>
</body>
</html>
