@extends('layouts.master') @section('css')
<style>
    .StripeElement {
        box-sizing: border-box;
        height: 40px;
        padding: 10px 12px;
        border: 1px solid transparent;
        border-radius: 4px;
        background-color: white;
        box-shadow: 0 1px 3px 0 #e6ebf1;
        -webkit-transition: box-shadow 150ms ease;
        transition: box-shadow 150ms ease;
    }
    .StripeElement--focus {
        box-shadow: 0 1px 3px 0 #cfd7df;
    }
    .StripeElement--invalid {
        border-color: #fa755a;
    }
    .StripeElement--webkit-autofill {
        background-color: #fefde5 !important;
    }
</style>
@endsection @section('content')

<div class="row">
    <div class="text-center">
        <h2>Product Details</h2>
        <hr />
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="">
                <h4>Product Name : {{ $product->name }}</h4>
                <p><b>Product Description : </b>{{ $product->description }}</p>
                <p><b>Product Price : </b>{{ $product->amount }} USD</p>
            </div>
        </div>
        <div class="col-md-6">
            <form action="{{route('processPayment', [$product, $product->amount])}}" method="POST" id="payment-form">
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="subscription-option">
                                <label for="plan-silver">
                                    <span class="plan-price">Amount : ${{$product->amount}}</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <label for="card-holder-name">Card Holder Name</label>
                <input id="card-holder-name" type="text" value="{{$user->name}}" disabled />
                @csrf
                <div class="form-row">
                    <label for="card-element">Credit or debit card</label>
                    <div id="card-element" class="form-control"></div>
                    <!-- Used to display form errors. -->
                    <div id="card-errors" role="alert"></div>
                </div>
                <div class="stripe-errors"></div>
                @if (count($errors) > 0)
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error) {{ $error }}<br />
                    @endforeach
                </div>
                @endif
                <div class="form-group mt-2">
                    <button type="button" id="card-button" data-secret="{{ $intent->client_secret }}" class="btn btn-sm btn-success">Pay Now</button>
                </div>
            </form>
        </div>
        <div class="row">
        &nbsp
        </div>
        <div class="row">&nbsp</div>
    </div>
</div>

@endsection @section('javaScript')
<script src="https://js.stripe.com/v3/"></script>
<script>
    var stripe = Stripe('{{ env('STRIPE_KEY') }}');
        var elements = stripe.elements();
        var style = {
            base: {
            color: '#32325d',
            fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
            fontSmoothing: 'antialiased',
            fontSize: '16px',
            '::placeholder': {
                    color: '#aab7c4'
                }
        },
        invalid: {
        color: '#fa755a',
        iconColor: '#fa755a'
        }
        };
        var card = elements.create('card', {hidePostalCode: true, style: style});
        card.mount('#card-element');
        console.log(document.getElementById('card-element'));
        card.addEventListener('change', function(event) {
        var displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        });
        const cardHolderName = document.getElementById('card-holder-name');
        const cardButton = document.getElementById('card-button');
        const clientSecret = cardButton.dataset.secret;
        cardButton.addEventListener('click', async (e) => {
        console.log("attempting");
            const { setupIntent, error } = await stripe.confirmCardSetup(
                clientSecret, {
                payment_method: {
                card: card,
                billing_details: { name: cardHolderName.value }
            }
        }
        );        
        if (error) {
            var errorElement = document.getElementById('card-errors');
            errorElement.textContent = error.message;
        } else {
            paymentMethodHandler(setupIntent.payment_method);
        }
        });
        function paymentMethodHandler(payment_method) {
            var form = document.getElementById('payment-form');
            var hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'payment_method');
            hiddenInput.setAttribute('value', payment_method);
            form.appendChild(hiddenInput);
            form.submit();
        }
</script>
@endsection
