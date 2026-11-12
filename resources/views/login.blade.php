@extends('layout')

@section('title', $pageContent->value('login', 'login', 'title', 'Sign in').' | '.config('store.name'))

@section('content')
    <div class="login-wrap">
        <section class="login-panel">
            <p class="eyebrow">{{ $pageContent->value('login', 'login', 'eyebrow', 'Your account') }}</p>
            <h1>{{ $pageContent->value('login', 'login', 'title', 'Sign in with your mobile') }}</h1>
            <p class="muted">{{ $pageContent->value('login', 'login', 'description', 'OTP delivery is not enabled in this demo. No code will be sent.') }}</p>
            <form method="POST" action="{{ route('login.otp') }}">
                @csrf
                <div class="field">
                    <label for="phone">Indian mobile number</label>
                    <input id="phone" name="phone" inputmode="numeric" autocomplete="tel-national" pattern="[6-9][0-9]{9}" maxlength="10" placeholder="10-digit mobile number" required>
                </div>
                <button class="button button--full" type="submit">{{ $pageContent->value('login', 'login', 'button_text', 'Request OTP') }}</button>
            </form>
            <p class="fine-print" style="margin-top: 16px">Connect an approved SMS provider, rate limits, secure session handling and privacy disclosures before enabling customer sign-in.</p>
            <a class="text-link" href="{{ route('account') }}">View account features →</a>
        </section>
    </div>
@endsection
