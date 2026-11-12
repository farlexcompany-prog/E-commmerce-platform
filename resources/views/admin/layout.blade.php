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

            <svg class="admin-icon-library" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
                <symbol id="admin-icon-dashboard" viewBox="0 0 24 24"><rect x="3" y="3" width="8" height="8" rx="1"/><rect x="14" y="3" width="7" height="5" rx="1"/><rect x="14" y="11" width="7" height="10" rx="1"/><rect x="3" y="14" width="8" height="7" rx="1"/></symbol>
                <symbol id="admin-icon-sales" viewBox="0 0 24 24"><path d="M6 3h12a2 2 0 0 1 2 2v16H4V5a2 2 0 0 1 2-2Z"/><path d="M8 8h8M8 12h8M8 16h5"/></symbol>
                <symbol id="admin-icon-catalog" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M3 9h18M8 9v11"/></symbol>
                <symbol id="admin-icon-customers" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/></symbol>
                <symbol id="admin-icon-cms" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="14" rx="2"/><path d="M8 22h8M12 18v4M7 14l3-3 2 2 3-4 2 5"/></symbol>
                <symbol id="admin-icon-offers" viewBox="0 0 24 24"><path d="m20 12-8 8-9-9V3h8l9 9Z"/><circle cx="7.5" cy="7.5" r="1"/></symbol>
                <symbol id="admin-icon-marketing" viewBox="0 0 24 24"><path d="M3 11v2a2 2 0 0 0 2 2h3l10 5V4L8 9H5a2 2 0 0 0-2 2Z"/><path d="m8 15 2 6h4l-2-8M21 9l-2 2 2 2"/></symbol>
                <symbol id="admin-icon-reporting" viewBox="0 0 24 24"><path d="M12 3v18M3 12h18"/><circle cx="12" cy="12" r="9"/></symbol>
                <symbol id="admin-icon-settings" viewBox="0 0 24 24"><path d="M12 8a4 4 0 1 0 0 8 4 4 0 0 0 0-8Z"/><path d="m19.4 15 .1.1 1.4 1.1-1.4 2.4-1.7-.7a8 8 0 0 1-1.7 1l-.3 1.8h-2.8l-.3-1.8a8 8 0 0 1-1.7-1l-1.7.7-1.4-2.4 1.4-1.1a7 7 0 0 1 0-2l-1.4-1.1 1.4-2.4 1.7.7a8 8 0 0 1 1.7-1l.3-1.8h2.8l.3 1.8a8 8 0 0 1 1.7 1l1.7-.7 1.4 2.4-1.4 1.1a7 7 0 0 1 0 2Z"/></symbol>
                <symbol id="admin-icon-configure" viewBox="0 0 24 24"><path d="M14.7 6.3a5 5 0 0 0-6.4 6.4L3 18a2.1 2.1 0 0 0 3 3l5.3-5.3a5 5 0 0 0 6.4-6.4L14 13l-3-3 3.7-3.7Z"/></symbol>
                <symbol id="admin-icon-chevron" viewBox="0 0 24 24"><path d="m7 10 5 5 5-5"/></symbol>
            </svg>

            <nav class="admin-nav" aria-label="Admin navigation">
                @foreach (config('admin.navigation') as $item)
                    @if (isset($item['route']))
                        <a class="admin-nav-link {{ request()->routeIs($item['route'], 'admin.orders.show') ? 'is-active' : '' }}" href="{{ route($item['route']) }}">
                            <svg aria-hidden="true"><use href="#admin-icon-{{ $item['icon'] }}"></use></svg>
                            <span>{{ $item['label'] }}</span>
                        </a>
                    @elseif (isset($item['children']))
                        @php
                            $activeSection = request()->route('section');
                            $catalogIsActive = in_array($activeSection, $item['children'], true);
                            $catalogIsOpen = $catalogIsActive || request()->routeIs('admin.dashboard', 'admin.orders.show');
                        @endphp
                        <details class="admin-nav-group" {{ $catalogIsOpen ? 'open' : '' }}>
                            <summary class="admin-nav-link {{ $catalogIsActive ? 'is-active' : '' }}">
                                <svg aria-hidden="true"><use href="#admin-icon-{{ $item['icon'] }}"></use></svg>
                                <span>{{ $item['label'] }}</span>
                                <svg class="admin-nav-chevron" aria-hidden="true"><use href="#admin-icon-chevron"></use></svg>
                            </summary>
                            <div class="admin-subnav">
                                @foreach ($item['children'] as $childKey)
                                    <a class="{{ $activeSection === $childKey ? 'is-active' : '' }}" href="{{ route('admin.sections.show', $childKey) }}">{{ config('admin.sections.'.$childKey.'.title') }}</a>
                                @endforeach
                            </div>
                        </details>
                    @else
                        @php
                            $sectionKey = $item['section'];
                            $section = config('admin.sections.'.$sectionKey);
                        @endphp
                        <a class="admin-nav-link {{ request()->route('section') === $sectionKey ? 'is-active' : '' }}" href="{{ route('admin.sections.show', $sectionKey) }}">
                            <svg aria-hidden="true"><use href="#admin-icon-{{ $item['icon'] }}"></use></svg>
                            <span>{{ $section['title'] }}</span>
                        </a>
                    @endif
                @endforeach
            </nav>
            <a class="admin-store-link" href="{{ route('store') }}">View storefront <span aria-hidden="true">↗</span></a>
            <p class="admin-sidebar-foot">Select a section to open its admin workspace.</p>
        </aside>

        <div class="admin-main">
            <header class="admin-topbar">
                <div>
                    <strong>@yield('section_title', 'Order desk')</strong>
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
