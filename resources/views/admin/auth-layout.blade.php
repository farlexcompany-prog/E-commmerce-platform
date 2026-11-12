<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Admin sign in') | {{ config('store.name') }}</title>
    <link rel="preload" href="{{ asset('fonts/plus-jakarta-sans-latin-variable.woff2') }}" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="{{ asset('fonts/inter-latin-variable.woff2') }}" as="font" type="font/woff2" crossorigin>
    <link rel="stylesheet" href="{{ asset('css/pharmacy.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body class="admin-auth-body">
    <main class="admin-auth-shell">
        <a class="admin-auth-brand" href="{{ route('store') }}">
            <img src="{{ asset('images/farlex-logo.png') }}" alt="{{ config('store.name') }}" width="145" height="84">
        </a>
        <section class="admin-auth-card">
            @yield('content')
        </section>
        <p class="admin-auth-note">Private access for authorized store staff.</p>
    </main>
</body>
</html>
