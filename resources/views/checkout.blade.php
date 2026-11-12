@extends('layout')

@section('title', $pageContent->value('checkout', 'heading', 'title', 'Delivery details').' | '.config('store.name'))

@section('content')
    <div class="container page-shell">
        <nav class="breadcrumbs" aria-label="Breadcrumb"><a href="{{ route('store') }}">Home</a><a href="{{ route('cart.index') }}">Cart</a><span aria-hidden="true">/</span><span>Checkout</span></nav>
        <header class="page-heading"><p class="eyebrow">{{ $pageContent->value('checkout', 'heading', 'eyebrow', 'Secure checkout layout · demo only') }}</p><h1>{{ $pageContent->value('checkout', 'heading', 'title', 'Delivery details') }}</h1><p>{{ $pageContent->value('checkout', 'heading', 'description', 'This prototype does not process payments or fulfil orders. Sample product data must be reviewed before launch.') }}</p></header>

        <div class="checkout-layout">
            <form class="panel" method="POST" action="{{ route('checkout.store') }}" enctype="multipart/form-data">
                @csrf
                <h2>{{ $pageContent->value('checkout', 'contact', 'title', 'Contact information') }}</h2>
                <div class="form-grid">
                    <div class="field">
                        <label for="customer_name">Full name</label>
                        <input id="customer_name" name="customer_name" value="{{ old('customer_name') }}" autocomplete="name" required maxlength="120">
                    </div>
                    <div class="field">
                        <label for="customer_phone">Mobile number</label>
                        <input id="customer_phone" name="customer_phone" inputmode="numeric" autocomplete="tel-national" value="{{ old('customer_phone') }}" pattern="[6-9][0-9]{9}" maxlength="10" placeholder="10-digit Indian number" required>
                    </div>
                    <div class="field field--full">
                        <label for="customer_email">Email address</label>
                        <input id="customer_email" type="email" name="customer_email" value="{{ old('customer_email') }}" autocomplete="email" required maxlength="255">
                    </div>
                    <div class="field field--full">
                        <label for="shipping_address">Delivery address</label>
                        <textarea id="shipping_address" name="shipping_address" rows="3" autocomplete="street-address" required maxlength="1000">{{ old('shipping_address') }}</textarea>
                    </div>
                    <div class="field">
                        <label for="pincode">Pincode</label>
                        <input id="pincode" name="pincode" inputmode="numeric" autocomplete="postal-code" value="{{ old('pincode') }}" pattern="[1-9][0-9]{5}" maxlength="6" required>
                        <small>Live address and serviceability lookup is not configured.</small>
                    </div>
                </div>

                @if ($hasRx)
                    <section class="prescription-upload field" aria-labelledby="prescription-title">
                        <h2 id="prescription-title">{{ $pageContent->value('checkout', 'prescription', 'title', 'Prescription required to continue') }}</h2>
                        <p>{{ $pageContent->value('checkout', 'prescription', 'description', 'Your cart contains an Rx-labelled product. Upload a clear prescription (PDF, JPG or PNG, up to 8 MB). A qualified pharmacy professional must review it before fulfilment.') }}</p>
                        <label for="prescription">Prescription file <span aria-hidden="true">*</span></label>
                        <input id="prescription" name="prescription" type="file" accept=".pdf,.jpg,.jpeg,.png,application/pdf,image/jpeg,image/png" required>
                        <small>Stored in private local storage in this demo; connect secure access, retention and review controls before launch.</small>
                    </section>
                @endif

                <section class="field">
                    <h2>{{ $pageContent->value('checkout', 'payment', 'title', 'Payment method') }}</h2>
                    <p class="muted">{{ $pageContent->value('checkout', 'payment', 'description', 'Options are for interface preview only. No payment is taken.') }}</p>
                    <div class="payment-options">
                        @foreach (['upi' => 'UPI', 'card' => 'Credit / debit card', 'net_banking' => 'Net banking', 'wallet' => 'Wallet', 'cod' => 'Cash on delivery'] as $value => $label)
                            <label class="payment-option"><input type="radio" name="payment_method" value="{{ $value }}" @checked(old('payment_method', 'upi') === $value)> {{ $label }}</label>
                        @endforeach
                    </div>
                </section>

                <label class="terms-check field">
                    <input type="checkbox" name="accept_terms" value="1" required @checked(old('accept_terms'))>
                    <span>I understand this is a demo order and agree to the <a class="text-link" href="{{ route('policies.show', 'terms') }}">review-draft terms</a>.</span>
                </label>
                <button class="button button--full" type="submit">{{ $pageContent->value('checkout', 'payment', 'submit', 'Place demo order') }} · {{ config('store.currency_symbol') }}{{ number_format($total / 100, 2) }}</button>
            </form>

            <aside class="panel summary-panel">
                <h2>Order summary</h2>
                @foreach ($items as $item)
                    <div class="summary-line"><span>{{ $item['product']->name }} × {{ $item['quantity'] }}</span><span>{{ config('store.currency_symbol') }}{{ number_format($item['line_total'] / 100, 2) }}</span></div>
                @endforeach
                <div class="summary-line"><span>MRP total</span><span>{{ config('store.currency_symbol') }}{{ number_format($mrpSubtotal / 100, 2) }}</span></div>
                <div class="summary-line"><span>Total savings</span><span>− {{ config('store.currency_symbol') }}{{ number_format($discount / 100, 2) }}</span></div>
                <div class="summary-line"><span>Delivery</span><span>{{ $deliveryFee === 0 ? 'FREE' : config('store.currency_symbol').number_format($deliveryFee / 100, 2) }}</span></div>
                <div class="summary-line summary-line--total"><span>Total</span><span>{{ config('store.currency_symbol') }}{{ number_format($total / 100, 2) }}</span></div>
                <p class="summary-note">This site is not currently a licensed or operating pharmacy. Confirm all company, licence and pharmacist details before publishing.</p>
                <a class="text-link" href="{{ route('cart.index') }}">← Return to cart</a>
            </aside>
        </div>
    </div>
@endsection
