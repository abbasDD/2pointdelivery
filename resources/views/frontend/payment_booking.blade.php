@extends('frontend.layouts.app')

@section('title', 'Booking')

@section('content')
    <div class="container py-5">
        <div class="row">
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
                        {{-- Priority Setting --}}
                        <div class="d-flex align-items-center justify-content-between">
                            <h6>Priority Setting:</h6>
                            <p>{{ $booking->prioritySetting->name }}</p>
                        </div>
                        {{-- Is Secureship Enabled --}}
                        <div class="d-flex align-items-center justify-content-between">
                            <h6>Secureship API Enabled:</h6>
                            <p>{{ $booking->is_secure_ship ? 'Yes' : 'No' }}</p>
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
                        {{-- Distance Price --}}
                        <div class="d-flex align-items-center justify-content-between">
                            <h6>Distance Price:</h6>
                            <p>${{ $bookingData->distance_price }}</p>
                        </div>
                        {{-- Weight Price --}}
                        <div class="d-flex align-items-center justify-content-between">
                            <h6>Weight Price:</h6>
                            <p>${{ $bookingData->weight_price }}</p>
                        </div>
                        {{-- Service Price --}}
                        <div class="d-flex align-items-center justify-content-between">
                            <h6>Service Price:</h6>
                            <p>${{ $bookingData->service_price }}</p>
                        </div>
                        @if ($booking->booking_type == 'delivery')
                            {{-- Vehicle Price --}}
                            <div class="d-flex align-items-center justify-content-between">
                                <h6>Vehicle Price:</h6>
                                <p>${{ $bookingData->vehicle_price }}</p>
                            </div>
                        @endif
                        {{-- Tax Price --}}
                        <div class="d-flex align-items-center justify-content-between">
                            <h6>Tax Price:</h6>
                            <p>${{ $bookingData->tax_price }}</p>
                        </div>
                        {{-- Total Price --}}
                        <div class="d-flex align-items-center justify-content-between">
                            <h6>Total Price:</h6>
                            <p>${{ $bookingData->total_price }}</p>
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
                                <p>{{ $bookingData->payment_method }}</p>
                            </div>
                        @endif

                        {{-- Seprator --}}
                        <hr>

                        @if ($booking->status == 'draft')
                            {{-- Payment Method --}}
                            <h5>Payment Now Using:</h5>
                            <div class=" d-flex align-items-center justify-content-center">
                                {{-- Paypal --}}
                                @if ($paypalEnabled)
                                    <div class="paypal">
                                        <form id="paypal-form" class="p-0 m-0"
                                            action="{{ route('client.booking.payment.paypal.create') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                                            <!-- Assuming $booking is available with the booking details -->

                                            <input type="hidden" name="total_price"
                                                value="{{ $bookingData->total_price }}">
                                            <!-- Assuming the total_price is fixed -->
                                            <button type="submit" class="btn btn-paypal d-flex align-items-center">
                                                <i class="fab fa-paypal"></i>
                                                <span class="d-none d-md-block ml-2">PayPal</span>
                                            </button>
                                        </form>
                                    </div>
                                @endif
                                @if ($stripeEnabled)
                                    {{-- Stripe --}}
                                    <div class="stripe ml-2">
                                        <button class="btn btn-stripe d-flex align-items-center"
                                            onclick="paymentMethodSelection('stripe')" target="_blank">
                                            <i class="fa-brands fa-stripe"></i>
                                            <span class="d-none d-md-block ml-2">Stripe</span>
                                        </button>
                                    </div>
                                @endif
                                {{-- Cash On Delivery --}}
                                <div class="cod ml-2">
                                    <button class="btn btn-primary d-flex align-items-center"
                                        onclick="paymentMethodSelection('cod')">
                                        <i class="fa-solid fa-money-bill-1"></i>
                                        <span class="d-none d-md-block ml-2">COD</span>
                                    </button>
                                </div>
                            </div>
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
    @endsection

    <script>
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
                        // Redirect to booking detail page
                        window.location.href = base_url + '/client/bookings/';
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
