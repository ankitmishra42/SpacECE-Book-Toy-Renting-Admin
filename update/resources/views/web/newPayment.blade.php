<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <script src="https://js.stripe.com/v3/"></script>
    <link rel="stylesheet" href="{{ asset('web/css/bootstrap.css') }}">
    <link href="{{ asset('web/css/stripe_checkout.css') }}" rel="stylesheet" />
</head>

<body>
    <div class="container">
        <div class="web-logo">
            <a href="https://goldstardrycleaners.com/"><img src="{{ asset('web/logos/logo.png') }}" alt=""
                    width="100%"></a>
        </div>

        <div class="row">
            <div class="col-md-6">

                <div class="stripe-container">
                    {{ csrf_field() }}
                    <form id="payment-form">
                        <div id="payment-element">
                            <!-- Stripe.js injects the Payment Element here-->
                        </div>
                        <button id="submit">
                            <div class="spinner hidden" id="spinner"></div>
                            <span id="button-text">Pay £9.99</span>
                        </button>
                        <div id="payment-message" class="hidden"></div>
                    </form>
                </div>

            </div>

            <div class="col-lg-6 m-auto">
                @if ($order->payment_status == 'Paid')
                    <div class="card text-white bg-success mb-3">
                        <div class="card-body">
                            <h2>Hello</h2>
                            <h3 class="card-title">We already received your payment</h3>
                            <p class="card-text text-lite">Some quick example text to build on the card title and make
                                up the bulk of the card's content.</p>
                            <h1 class="card-title">Thanks for stay with us.</h1>
                        </div>
                    </div>
                @endif
                <div class="card rounded-0 border-0">
                    <div class="card-header">
                        <h3 class="panel-title display-td">Order Details</h3>
                    </div>
                    <div class="card-body">

                        <div class="card mb-2">
                            <div class="card-header py-2">
                                <h4 class="panel-title display-td">Customer</h4>
                            </div>
                            <div class="card-body">
                                <h4 class="mb-0 order">
                                    Name : <span class="right">{{ $order->customer->user->name }}</span>
                                </h4>
                                <p class="mb-0 order">
                                    Phone :<span class="right">{{ $order->customer->user->mobile }}</span>
                                </p>
                                <p class="mb-0 order">
                                    Email :<span class="right">{{ $order->customer->user->email }}</span>
                                </p>
                            </div>
                        </div>
                        @php
                            $freeDelivery = $deliveryCost ? $deliveryCost->fee_cost : 00;
                            $deliveryCharge = $deliveryCost ? $deliveryCost->cost : 00;
                        @endphp
                        <div class="card">
                            <div class="card-header py-2">
                                <h4 class="panel-title display-td">Order</h4>
                            </div>
                            <div class="card-body">
                                <h4 class="mb-0 order">
                                    Order ID <span>#{{ $order->prefix . $order->order_code }}</span>
                                </h4>
                                <p class="mb-0 order">
                                    Picked At : <span>{{ parse($order->pick_date, 'd M, Y') }} -
                                        {{ substr($order->pick_hour, 0, 5) }}</span>
                                </p>

                                <p class="mb-0 order">
                                    Delivery At :
                                    <span>{{parse($order->delivery_date, 'd M, Y') }} -
                                        {{ substr($order->delivery_hour, 0, 5) }}</span>
                                </p>

                                <p class="mb-0 order">
                                    Delivery Status : <span>{{ $order->order_status }}</span>
                                </p>

                                <p class="mb-0 order">
                                    Total Product : <span>{{ $order->products->count() }}</span>
                                </p>

                                <p class="mb-0 order">
                                    Sub total : <span>{{ currencyPosition($order->amount) }}</span>
                                </p>

                                <p class="mb-0 order">
                                    Delivery Cost :
                                    <span>{{ currencyPosition($order->amount <= $freeDelivery ? $deliveryCharge : '00') }}</span>
                                </p>

                                <p class="mb-0 order">
                                    Discount : <span class="text-danger">
                                        -{{ currencyPosition($order->discount) }}</span>
                                </p>

                                <p class="mb-0 order text-dark border-top pt-2">
                                    Total Amount : <span>{{ currencyPosition($order->total_amount) }}</span>
                                </p>

                                <div class="col-md-12 pr-0 mt-2 text-right">
                                    {{-- <button class="btn btn-primary btn-lg px-5 paynow" id="submit" >Pay Now
                                            ({{ config('enums.currency')[0] . $order->total_amount }})</button> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        // Replace with your public key, found in your Stripe dashboard
        const stripe = Stripe("{{ config('app.stripe_key') }}");

        const items = [{
            id: 'tshirt'
        }];

        let elements;

        initialize();
        checkStatus();

        document
            .querySelector("#payment-form")
            .addEventListener("submit", handleSubmit);

        async function initialize() {
            const {
                clientSecret
            } = await fetch("/payCharge", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value,
                },
                body: JSON.stringify({
                    items
                }),
            }).then((r) => r.json());

            elements = stripe.elements({
                clientSecret
            });

            const paymentElement = elements.create("payment");
            paymentElement.mount("#payment-element");
        }

        async function handleSubmit(e) {
            e.preventDefault();
            setLoading(true);

            const {
                error
            } = await stripe.confirmPayment({
                elements,
                confirmParams: {
                    // Replace with your payment completion page
                    return_url: "http://localhost/success",
                },
            });

            if (error.type === "card_error" || error.type === "validation_error") {
                showMessage(error.message);
            } else {
                showMessage("An unexpected error occured.");
            }

            setLoading(false);
        }

        async function checkStatus() {
            const clientSecret = new URLSearchParams(window.location.search).get(
                "payment_intent_client_secret"
            );

            if (!clientSecret) {
                return;
            }

            const {
                paymentIntent
            } = await stripe.retrievePaymentIntent(clientSecret);

            switch (paymentIntent.status) {
                case "succeeded":
                    showMessage("Payment succeeded!");
                    break;
                case "processing":
                    showMessage("Your payment is processing.");
                    break;
                case "requires_payment_method":
                    showMessage("Your payment was not successful, please try again.");
                    break;
                default:
                    showMessage("Something went wrong.");
                    break;
            }
        }

        // ------- UI helpers -------

        function showMessage(messageText) {
            const messageContainer = document.querySelector("#payment-message");

            messageContainer.classList.remove("hidden");
            messageContainer.textContent = messageText;

            setTimeout(function() {
                messageContainer.classList.add("hidden");
                messageText.textContent = "";
            }, 4000);
        }

        function setLoading(isLoading) {
            if (isLoading) {
                document.querySelector("#submit").disabled = true;
                document.querySelector("#spinner").classList.remove("hidden");
                document.querySelector("#button-text").classList.add("hidden");
            } else {
                document.querySelector("#submit").disabled = false;
                document.querySelector("#spinner").classList.add("hidden");
                document.querySelector("#button-text").classList.remove("hidden");
            }
        }
    </script>
</body>

</html>
