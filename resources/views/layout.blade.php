<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Everyday Goods'))</title>
    <style>
        :root { color-scheme: light; --ink: #1d2925; --muted: #64716b; --paper: #f7f7f2; --card: #fff; --green: #245c49; --line: #e3e7df; }
        * { box-sizing: border-box; }
        body { margin: 0; background: var(--paper); color: var(--ink); font: 16px/1.55 system-ui, -apple-system, "Segoe UI", sans-serif; }
        a { color: inherit; }
        .shell { max-width: 1120px; margin: auto; padding: 0 24px; }
        .topbar { background: var(--card); border-bottom: 1px solid var(--line); }
        .nav { min-height: 72px; display: flex; align-items: center; justify-content: space-between; gap: 20px; }
        .brand { color: var(--green); font-size: 1.15rem; font-weight: 800; letter-spacing: -.03em; text-decoration: none; }
        .nav a:last-child { font-weight: 650; text-decoration: none; }
        main { padding: 38px 0 72px; }
        h1, h2, p { margin-top: 0; }
        h1 { font-size: clamp(2rem, 5vw, 3.25rem); line-height: 1.08; letter-spacing: -.045em; }
        h2 { letter-spacing: -.025em; }
        .intro { max-width: 650px; margin-bottom: 34px; }
        .muted, .description { color: var(--muted); }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(230px, 1fr)); gap: 18px; }
        .card, .panel { background: var(--card); border: 1px solid var(--line); border-radius: 14px; padding: 22px; }
        .product-mark { display: grid; place-items: center; height: 145px; margin: -22px -22px 18px; border-radius: 14px 14px 0 0; background: #e7eee8; color: var(--green); font-size: 3rem; }
        .product-mark span { font-size: 3.2rem; }
        .product-name { margin-bottom: 7px; font-size: 1.15rem; }
        .description { min-height: 50px; font-size: .94rem; }
        .row { display: flex; align-items: center; justify-content: space-between; gap: 14px; }
        .price { font-weight: 750; font-size: 1.1rem; }
        button, .button { display: inline-block; border: 0; border-radius: 8px; padding: 10px 15px; background: var(--green); color: white; font: inherit; font-weight: 700; text-decoration: none; cursor: pointer; }
        button:hover, .button:hover { background: #194533; }
        .button.secondary { background: #eaf0eb; color: var(--green); }
        .button.danger { background: #f8e9e5; color: #983d2f; padding: 8px 11px; }
        .flash { margin: 0 0 22px; padding: 12px 15px; border-radius: 8px; background: #e7f2ea; color: #245c49; }
        .stack { display: grid; gap: 15px; }
        .cart-item { display: grid; grid-template-columns: 1fr auto auto; align-items: center; gap: 20px; }
        .cart-item h2 { margin: 0 0 4px; font-size: 1rem; }
        .inline { display: flex; align-items: center; gap: 8px; }
        input, textarea { width: 100%; padding: 10px 12px; border: 1px solid #cfd7ce; border-radius: 7px; font: inherit; }
        input[type=number] { width: 82px; }
        label { display: block; margin-bottom: 6px; font-weight: 650; }
        .field { margin-bottom: 16px; }
        .summary { border-top: 1px solid var(--line); margin-top: 18px; padding-top: 18px; }
        .narrow { max-width: 650px; }
        footer { padding: 22px 0; border-top: 1px solid var(--line); color: var(--muted); font-size: .9rem; }
        @media (max-width: 640px) { .shell { padding: 0 16px; } main { padding-top: 28px; } .cart-item { grid-template-columns: 1fr auto; } .cart-item form:first-of-type { grid-column: 1 / -1; } }
    </style>
</head>
<body>
    <header class="topbar">
        <nav class="shell nav">
            <a class="brand" href="{{ route('store') }}">{{ config('app.name', 'Everyday Goods') }}</a>
            <a href="{{ route('cart.index') }}">Cart ({{ array_sum(session('cart', [])) }})</a>
        </nav>
    </header>
    <main class="shell">
        @if (session('status'))
            <div class="flash" role="status">{{ session('status') }}</div>
        @endif
        @yield('content')
    </main>
    <footer><div class="shell">A starter storefront powered by Laravel.</div></footer>
</body>
</html>
