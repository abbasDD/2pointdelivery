@extends('client.layouts.app')

@section('title', 'Track Booking')

@section('content')

    <div class="container mb-5 p-3">
        <h5>Track Booking</h5>
        <div class="row">
            <div class="col-md-9">
                <input class="form-control" type="text" name="order_id" id="order_id" placeholder="Enter order id"
                    value="{{ isset($booking) ? $booking->uuid : '' }}" required>
            </div>
            <div class="col-md-3">
                <button class="btn btn-primary w-100" onclick="trackOrder()">Track</button>
            </div>
            {{-- Show error for invalid order --}}
            @if (!isset($booking))
                <div class="col-md-12">
                    <p class="text-danger m-2"> Please enter valid order </p>
                </div>
            @endif
        </div>
        {{-- Order Detail Section  --}}
        <section class="py-3">
            <div class="container">
                @if (isset($booking) && isset($bookingPayment))
                    <div class="row">
                        <div class="col-md-4">
                            {{-- Load Tracking Status --}}
                            @include('frontend.bookings.partials.show.tracking')
                        </div>
                        <div class="col-md-8">
                            {{-- Map Tracking --}}
                            @include('frontend.bookings.partials.show.map')
                        </div>
                    </div>
                @endif
            </div>
        </section>

        <script>
            function trackOrder() {
                var order_id = document.getElementById("order_id").value;

                // if empty show error
                if (!order_id) {
                    alert('Please fill up order id');
                    return false;
                }

                // Redirect to same page with id
                window.location.href = "{{ route('client.trackOrder') }}/" + order_id;

                service = new google.maps.places.PlacesService(map);
                service.textSearch({
                    query: order_id
                }, callback);
            }
        </script>

    @endsection
