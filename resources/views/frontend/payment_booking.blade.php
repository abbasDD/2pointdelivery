@extends('frontend.layouts.app')

@section('title', 'Booking')

@section('content')
    <div class="container py-5">
        <div class="row">
            {{-- Show bookingTimeLeft --}}
            <div class="col-md-12 my-5">
                <div class="text-center">
                    <h4>Time Left: <span id="timer" class="text-primary">{{ $bookingTimeLeft }}</span>
                    </h4>
                </div>
            </div>

            {{-- Booking Details --}}
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Booking Details</h5>
                    </div>
                    <div class="card-body">
                        {{-- Booking ID --}}
                        <div class="d-flex align-items-center justify-content-between">
                            <h6>Booking ID:</h6>
                            <p class="text-uppercase">{{ $booking->uuid }}</p>
                        </div>
                        {{-- Pickup Address --}}
                        <div class="d-flex align-items-center justify-content-between">
                            <h6>Pickup Address:</h6>
                            <p>{{ $booking->pickup_address }}</p>
                        </div>
                        {{-- Dropoff Address --}}
                        <div class="d-flex align-items-center justify-content-between">
                            <h6>Dropoff Address:</h6>
                            <p>{{ $booking->dropoff_address }}</p>
                        </div>
                        {{-- Booking Date Time --}}
                        <div class="d-flex align-items-center justify-content-between">
                            <h6>Pickup Date Time:</h6>
                            <p>{{ $booking->booking_at ? $booking->booking_at : '-' }}</p>
                        </div>
                        {{-- Service Type --}}
                        <div class="d-flex align-items-center justify-content-between">
                            <h6>Service Type:</h6>
                            <p>{{ $booking->serviceType->name }}</p>
                        </div>
                        {{-- Service Category --}}
                        <div class="d-flex align-items-center justify-content-between">
                            <h6>Service Category:</h6>
                            <p>{{ $booking->serviceCategory->name }}</p>
                        </div>
                        {{-- Is Secureship Enabled --}}
                        <div class="d-flex align-items-center justify-content-between">
                            <h6>Secureship API Enabled:</h6>
                            <p>{{ $booking->booking_type == 'secureship' ? 'Yes' : 'No' }}</p>
                        </div>
                        {{-- $booking->distance_in_km  --}}
                        <div class="d-flex align-items-center justify-content-between">
                            <h6>Distance:</h6>
                            <p>{{ $booking->distance_in_km }} km</p>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Payment Method --}}
            <div class="col-md-6">
                {{-- Payment Information --}}
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Payment Information</h5>
                    </div>
                    <div class="card-body">
                        {{-- Service Price --}}
                        <div class="d-flex align-items-center justify-content-between">
                            <h6>Service Price:</h6>
                            @if ($booking->booking_type == 'secureship')
                                <p>${{ $bookingData->subTotal }}</p>
                            @else
                                <p>${{ $bookingData->sub_total }}</p>
                            @endif
                        </div>
                        {{-- Tax Price --}}
                        <div class="d-flex align-items-center justify-content-between">
                            <h6>Tax Price:</h6>
                            @if ($booking->booking_type == 'secureship')
                                <p>${{ $bookingData->taxAmount }}</p>
                            @else
                                <p>${{ $bookingData->tax_price }}</p>
                            @endif
                        </div>
                        {{-- Total Price --}}
                        <div class="d-flex align-items-center justify-content-between">
                            <h6>Total Price:</h6>
                            @if ($booking->booking_type == 'secureship')
                                <p>${{ $bookingData->grandTotal }}</p>
                            @else
                                <p>${{ $bookingData->total_price }}</p>
                            @endif
                        </div>
                        @if ($bookingData->payment_status == 'unpaid')
                            {{-- Payment Status --}}
                            <div class="d-flex align-items-center justify-content-between">
                                <h6>Payment Status:</h6>
                                <p>{{ $bookingData->payment_status }}</p>
                            </div>
                            {{-- Payment Method --}}
                            <div class="d-flex align-items-center justify-content-between">
                                <h6>Payment Method:</h6>
                                <p>{{ $bookingData->payment_method ?? '-' }}</p>
                            </div>
                        @endif

                        {{-- Seprator --}}
                        <hr>

                        @if ($booking->status == 'draft')
                            @if ($booking->booking_type == 'secureship')
                                <p class="text-danger"> Unable to process payment for Secureship </p>
                            @else
                                <div id="paymentMethodDiv">
                                    {{-- Payment Method --}}
                                    <h5>Payment Now Using:</h5>
                                    <div class=" d-flex align-items-center justify-content-center">
                                        {{-- Paypal --}}
                                        @if ($paypalEnabled)
                                            <div class="paypal">
                                                <form id="paypal-form" class="p-0 m-0"
                                                    action="{{ route('client.booking.payment.paypal.create') }}"
                                                    method="post">
                                                    @csrf
                                                    <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                                                    <!-- Assuming $booking is available with the booking details -->
                                                    @if ($booking->booking_type == 'secureship')
                                                        <input type="hidden" name="total_price"
                                                            value="{{ $bookingData->grandTotal }}">
                                                    @else
                                                        <input type="hidden" name="total_price"
                                                            value="{{ $bookingData->total_price }}">
                                                    @endif

                                                    <!-- Assuming the total_price is fixed -->
                                                    <button type="submit" class="btn btn-paypal d-flex align-items-center">
                                                        <i class="fab fa-paypal"></i>
                                                        <span class="d-none d-md-block ml-2">PayPal</span>
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                        {{-- Stripe --}}
                                        @if ($stripeEnabled && $stripe_publishable_key != null)
                                            <div class="stripe ml-2">
                                                <button class="btn btn-stripe d-flex align-items-center"
                                                    onclick="openStripePaymentModal()">
                                                    <i class="fa-brands fa-stripe"></i>
                                                    <span class="d-none d-md-block ml-2">Stripe</span>
                                                </button>
                                            </div>
                                        @endif
                                        {{-- Cash On Delivery --}}
                                        @if ($codEnabled)
                                            <div class="cod ml-2">
                                                <button class="btn btn-primary d-flex align-items-center"
                                                    onclick="paymentMethodSelection('cod')">
                                                    <i class="fa-solid fa-money-bill-1"></i>
                                                    <span class="d-none d-md-block ml-2">COD</span>
                                                </button>
                                            </div>
                                        @endif
                                        {{-- <div class="ml-2">
                                        <button class="btn btn-primary d-flex align-items-center"
                                            onclick="openCODModal()">Open</button>
                                    </div> --}}
                                    </div>
                                </div>
                            @endif
                        @else
                            <h6 class="text-center">Payment Successful</h6>
                            {{-- Link to Booking Show --}}
                            <div class="d-flex align-items-center justify-content-center">
                                <a href="{{ route('client.booking.show', $booking->id) }}"
                                    class="btn btn-primary d-flex align-items-center">
                                    <i class="fa-solid fa-eye"></i>
                                    <span class="d-none d-md-block ml-2">View Booking</span>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Cash On Delivery Modal --}}
        <div class="modal fade" id="codModal" tabindex="-1" role="dialog" aria-labelledby="codModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content card">
                    <div class="modal-body">
                        <div class="">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-center">
                                    <div class="bg-primary rounded-1 text-white p-3 mb-3">
                                        <i class="fas fa-check fa-3x"></i>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <h3 class="mb-1">Payment Successful</h3>
                                    <p class="mb-3">Thank you for using our service. Please check your email for more
                                        details.</p>
                                    <a class="btn btn-primary" href="{{ route('client.bookings') }}">View Booking</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Stripe Payment Modal --}}
    <div class="modal fade" id="stripePaymentModal" tabindex="-1" role="dialog" aria-labelledby="stripePaymentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <h3>Stripe Payment</h3>
                    <form action="{{ route('client.booking.payment.stripe.charge') }}" method="POST" id="payment-form">
                        {{ csrf_field() }}
                        <div class="d-flex align-items-center justify-content-between">
                            <p>Amount to Pay: </p>
                            <p>{{ $bookingData->total_price }}</p>
                        </div>

                        <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                        <input type="hidden" name="amount" value="{{ $bookingData->total_price * 100 }}">

                        <div class="d-flex align-items-center justify-content-between">
                            <p>Payment Method: </p>
                            <p>Stripe</p>
                        </div>

                        <label for="card-element">
                            Credit or debit card
                        </label>
                        <div id="card-element">
                            <!-- A Stripe Element will be inserted here. -->
                        </div>

                        <!-- Used to display form errors. -->
                        <div id="card-errors" role="alert"></div>

                        <button class="btn btn-primary mt-3" type="submit">Submit Payment</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

<script>
    // Open COD Modal
    function openCODModal() {
        $('#codModal').modal('show');
    }

    // openStripePaymentModal
    function openStripePaymentModal() {
        $('#stripePaymentModal').modal('show');
        // Create a Stripe client
        var stripe_publishable_key = '{{ $stripe_publishable_key }}';
        console.log(stripe_publishable_key);
        var stripe = Stripe(stripe_publishable_key);

        // Create an instance of Elements
        var elements = stripe.elements();

        // Custom styling can be passed to options when creating an Element.
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

        // Create an instance of the card Element
        var card = elements.create('card', {
            style: style
        });

        // Add an instance of the card Element into the `card-element` <div>
        card.mount('#card-element');

        // Handle real-time validation errors from the card Element
        card.on('change', function(event) {
            var displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        });

        // Handle form submission
        var form = document.getElementById('payment-form');
        form.addEventListener('submit', function(event) {
            event.preventDefault();

            stripe.createToken(card).then(function(result) {
                if (result.error) {
                    // Inform the user if there was an error
                    var errorElement = document.getElementById('card-errors');
                    errorElement.textContent = result.error.message;
                } else {
                    // Send the token to your server
                    stripeTokenHandler(result.token);
                }
            });
        });
    }

    // Call paymentMethodSelection
    function paymentMethodSelection(method) {
        switch (method) {
            case 'paypal':
                alert('Not Implemented Yet');
                break;
            case 'stripe':
                alert('Not Implemented Yet');
                break;
            case 'cod':
                // Call a function to ajax call
                codPayementSelection();
                break;
            default:
                break;
        }
    }

    // Call codPayementSelection
    function codPayementSelection() {
        // base url
        const base_url = "{{ url('/') }}";
        // Call a function to ajax call
        $.ajax({
            type: 'GET',
            url: '{{ route('client.booking.payment.cod', $booking->id) }}',
            success: function(response) {
                // Handle the response
                console.log(response); // Log the response for debugging
                if (response.success == true) {
                    // Show a success modal
                    $('#codModal').modal('show');
                    // display none paymentMethodDiv 
                    $('#paymentMethodDiv').css('display', 'none');
                    // Wait for 5 seconds
                    setTimeout(function() {
                        // Redirect to booking detail page
                        window.location.href = base_url + '/client/bookings/';
                    }, 3000);
                    // Redirect to booking detail page
                    // window.location.href = base_url + '/client/bookings/';
                } else {
                    alert('Something went wrong. Please try again later.');
                }
            },
            error: function(xhr, status, error) {
                // Handle the error
                console.error(error); // Log the error for debugging
            }
        })
    }
</script>

<script src="https://js.stripe.com/v3/"></script>
<script>
    // Submit the form with the Stripe token
    function stripeTokenHandler(token) {
        // Insert the token ID into the form so it gets submitted to the server
        var form = document.getElementById('payment-form');
        var hiddenInput = document.createElement('input');
        hiddenInput.setAttribute('type', 'hidden');
        hiddenInput.setAttribute('name', 'stripeToken');
        hiddenInput.setAttribute('value', token.id);
        form.appendChild(hiddenInput);

        // Submit the form
        form.submit();
    }
</script>

@if (isset($bookingTimeLeft))
    {{-- Timer Javascript --}}
    <script>
        // Get the initial time from Blade
        const initialTime = '{{ $bookingTimeLeft }}';

        // Convert the initial time to seconds
        let seconds = timeToSeconds(initialTime);

        // Function to convert time format to seconds
        function timeToSeconds(time) {
            const [hours, minutes, seconds] = time.split(':').map(Number);
            return hours * 3600 + minutes * 60 + seconds;
        }

        // Function to update the timer display
        function updateTimer() {
            const hours = Math.floor(seconds / 3600);
            const minutes = Math.floor((seconds % 3600) / 60);
            const remainingSeconds = seconds % 60;
            document.getElementById('timer').innerText =
                `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${remainingSeconds.toString().padStart(2, '0')}`;
            seconds--;

            // If time is 00:00:00, redirect to bookings
            if (hours === 0 && minutes === 0 && remainingSeconds === 0) {
                window.location.href = "{{ route('client.bookings') }}";
            }


        }

        // Start the timer
        setInterval(updateTimer, 1000); // Update every second
    </script>
@endif
