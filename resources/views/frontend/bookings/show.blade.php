@extends('frontend.layouts.app')

@section('title', 'Booking Detail')

@section('content')

    {{-- Header Section  --}}
    <section class="py-3">
        <div class="container mt-5">
            <div class="d-flex align-items-center justify-content-between">
                <div class="">
                    {{-- Back to Bookings --}}
                    <a href="{{ $clientView ? route('client.bookings') : route('helper.bookings') }}"
                        class="btn btn-primary btn-sm"><i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
                        <span class="d-none d-md-inline"> Back to Bookings</span></a>
                    <h3 class="mb-1">Order Detail</h3>
                    <p>Order No : <span class="text-uppercase">{{ $booking->uuid ? $booking->uuid : '-' }}</span></p>
                </div>
                {{-- Action buttons --}}
                @include('frontend.bookings.partials.show.actions')
            </div>
    </section>


    {{-- Order Detail Section  --}}
    <section class="py-3">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    {{-- Load Tracking Status --}}
                    @include('frontend.bookings.partials.show.tracking')

                    {{-- Load Images --}}
                    @include('frontend.bookings.partials.show.images')
                </div>
                <div class="col-md-8">
                    {{-- Map Tracking --}}
                    <div class="card mb-3">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="mb-0">Tracking Order</h5>
                            <p class="mb-0 badge bg-primary">{{ $booking->status }}</p>
                        </div>
                        <div class="card-body" style="height: 400px">
                            <div id="map" style="height:100%; width:100%;"></div>
                            <script
                                src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places&callback=initMap"
                                async defer></script>
                            <script>
                                var map;
                                var directionsService;
                                var directionsRenderer;
                                var defaultPickupLat = '{{ $booking->pickup_latitude ? $booking->pickup_latitude : 33.6115 }}';
                                var defaultPickupLng = '{{ $booking->pickup_longitude ? $booking->pickup_longitude : 72.9706 }}';
                                var defaultDeliveryLat = '{{ $booking->dropoff_latitude ? $booking->dropoff_latitude : 33.6115 }}';
                                var defaultDeliveryLng = '{{ $booking->dropoff_longitude ? $booking->dropoff_longitude : 72.9706 }}';

                                function initMap() {
                                    map = new google.maps.Map(document.getElementById('map'), {
                                        center: {
                                            lat: defaultPickupLat,
                                            lng: defaultPickupLng
                                        },
                                        zoom: 11
                                    });

                                    directionsService = new google.maps.DirectionsService();
                                    directionsRenderer = new google.maps.DirectionsRenderer({
                                        map: map,
                                        polylineOptions: {
                                            strokeColor: '#038164',
                                            strokeOpacity: 1.0,
                                            strokeWeight: 5
                                        }
                                    });

                                    updateRoute();

                                }

                                function updateRoute() {
                                    var pickupLat = parseFloat(defaultPickupLat);
                                    var pickupLng = parseFloat(defaultPickupLng);
                                    var deliveryLat = parseFloat(defaultDeliveryLat);
                                    var deliveryLng = parseFloat(defaultDeliveryLng);

                                    var pickup = new google.maps.LatLng(pickupLat, pickupLng);
                                    var delivery = new google.maps.LatLng(deliveryLat, deliveryLng);

                                    var request = {
                                        origin: pickup,
                                        destination: delivery,
                                        travelMode: google.maps.TravelMode.DRIVING
                                    };

                                    directionsService.route(request, function(result, status) {
                                        if (status == google.maps.DirectionsStatus.OK) {
                                            directionsRenderer.setDirections(result);
                                        } else {
                                            console.error('Error fetching directions:', status);
                                        }
                                    });
                                }
                            </script>
                        </div>
                    </div>

                    <div class="card mb-3">
                        <div class="card-body">
                            {{-- Include Order Summary from frontend\bookings\partials\info.blade.php --}}
                            @include('frontend.bookings.partials.info')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


@endsection
