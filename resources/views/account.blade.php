@extends('layout')

@section('title', 'Account | '.config('store.name'))

@section('content')
    <div class="container page-shell">
        <nav class="breadcrumbs" aria-label="Breadcrumb"><a href="{{ route('store') }}">Home</a><span aria-hidden="true">/</span><span>Account</span></nav>
        <header class="page-heading"><p class="eyebrow">Customer account</p><h1>Your health essentials, in one place.</h1><p>Customer accounts are not active until OTP delivery and secure authentication are configured.</p><a class="button" href="{{ route('login') }}">Sign in</a></header>
        <div class="account-grid">
            <article class="account-card"><h2>My orders</h2><p>Order history and tracking appear after verified account sign-in is implemented.</p></article>
            <article class="account-card"><h2>Saved prescriptions</h2><p>Not enabled. Prescription files require reviewed access and retention controls.</p></article>
            <article class="account-card"><h2>Addresses</h2><p>Saved addresses are not enabled in this demo.</p></article>
            <article class="account-card"><h2>Wishlist</h2><p>Save products temporarily in this browser session.</p><a class="text-link" href="{{ route('wishlist.index') }}">Open wishlist →</a></article>
            <article class="account-card"><h2>Refill reminders</h2><p>Reminder scheduling is not connected.</p></article>
            <article class="account-card"><h2>Profile</h2><p>Profile details are not collected until account security is configured.</p></article>
        </div>
    </div>
@endsection
