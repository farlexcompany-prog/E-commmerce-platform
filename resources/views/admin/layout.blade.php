<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Order desk') | {{ config('store.name') }}</title>
    <link rel="preload" href="{{ asset('fonts/plus-jakarta-sans-latin-variable.woff2') }}" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="{{ asset('fonts/inter-latin-variable.woff2') }}" as="font" type="font/woff2" crossorigin>
    <link rel="stylesheet" href="{{ asset('css/pharmacy.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body class="admin-body">
    <div class="admin-shell">
        <aside class="admin-sidebar">
            <a class="admin-brand" href="{{ route('admin.dashboard') }}">
                <img src="{{ asset('images/farlex-logo.png') }}" alt="{{ config('store.name') }}" width="145" height="84">
            </a>
            <p class="admin-side-caption">ORDER MANAGEMENT</p>
            <nav class="admin-nav" aria-label="Admin navigation">
                <a class="{{ request()->routeIs('admin.dashboard', 'admin.orders.show') ? 'is-active' : '' }}" href="{{ route('admin.dashboard') }}">
                    <span aria-hidden="true">▤</span> Orders
                </a>
                <a href="{{ route('store') }}"><span aria-hidden="true">↗</span> View storefront</a>
            </nav>
            <p class="admin-sidebar-foot">Customer order information is private. Sign out when finished.</p>
        </aside>

        <div class="admin-main">
            <header class="admin-topbar">
                <div>
                    <strong>Order desk</strong>
                    <span>Signed in as {{ auth('admin')->user()->username }}</span>
                </div>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button class="admin-signout" type="submit">Sign out</button>
                </form>
            </header>

            <main class="admin-content">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
