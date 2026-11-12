@extends('layout')

@section('title', $pageContent->value('account', 'heading', 'title', 'Account').' | '.config('store.name'))

@section('content')
    <div class="container page-shell">
        <nav class="breadcrumbs" aria-label="Breadcrumb"><a href="{{ route('store') }}">Home</a><span aria-hidden="true">/</span><span>Account</span></nav>
        <header class="page-heading"><p class="eyebrow">{{ $pageContent->value('account', 'heading', 'eyebrow', 'Customer account') }}</p><h1>{{ $pageContent->value('account', 'heading', 'title', 'Your health essentials, in one place.') }}</h1><p>{{ $pageContent->value('account', 'heading', 'description', 'Customer accounts are not active until OTP delivery and secure authentication are configured.') }}</p><a class="button" href="{{ route('login') }}">Sign in</a></header>
        <div class="account-grid">
            <article class="account-card"><h2>{{ $pageContent->value('account', 'features', 'orders_title', 'My orders') }}</h2><p>{{ $pageContent->value('account', 'features', 'orders_copy', 'Order history and tracking appear after verified account sign-in is implemented.') }}</p></article>
            <article class="account-card"><h2>{{ $pageContent->value('account', 'features', 'prescriptions_title', 'Saved prescriptions') }}</h2><p>{{ $pageContent->value('account', 'features', 'prescriptions_copy', 'Not enabled. Prescription files require reviewed access and retention controls.') }}</p></article>
            <article class="account-card"><h2>{{ $pageContent->value('account', 'features', 'addresses_title', 'Addresses') }}</h2><p>{{ $pageContent->value('account', 'features', 'addresses_copy', 'Saved addresses are not enabled in this demo.') }}</p></article>
            <article class="account-card"><h2>{{ $pageContent->value('account', 'features', 'wishlist_title', 'Wishlist') }}</h2><p>{{ $pageContent->value('account', 'features', 'wishlist_copy', 'Save products temporarily in this browser session.') }}</p><a class="text-link" href="{{ route('wishlist.index') }}">Open wishlist →</a></article>
            <article class="account-card"><h2>{{ $pageContent->value('account', 'features', 'reminders_title', 'Refill reminders') }}</h2><p>{{ $pageContent->value('account', 'features', 'reminders_copy', 'Reminder scheduling is not connected.') }}</p></article>
            <article class="account-card"><h2>{{ $pageContent->value('account', 'features', 'profile_title', 'Profile') }}</h2><p>{{ $pageContent->value('account', 'features', 'profile_copy', 'Profile details are not collected until account security is configured.') }}</p></article>
        </div>
    </div>
@endsection
