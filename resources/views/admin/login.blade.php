@extends('admin.auth-layout')

@section('title', 'Admin sign in')

@section('content')
    <p class="eyebrow">Farlex order desk</p>
    <h1>Sign in</h1>
    <p class="admin-auth-intro">View customer orders and the products they purchased.</p>

    @if ($errors->any())
        <div class="admin-form-error" role="alert">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form class="admin-login-form" method="POST" action="{{ route('admin.login.store') }}">
        @csrf
        <label for="admin-username">Username</label>
        <input id="admin-username" name="username" type="text" value="{{ old('username') }}" autocomplete="username" maxlength="40" required autofocus>

        <label for="admin-password">Password</label>
        <input id="admin-password" name="password" type="password" autocomplete="current-password" required>

        <button class="admin-primary-button" type="submit">Sign in to orders</button>
    </form>

    <a class="admin-back-link" href="{{ route('store') }}">← Back to storefront</a>
@endsection
