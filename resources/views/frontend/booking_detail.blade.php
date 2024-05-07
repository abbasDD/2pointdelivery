@extends('frontend.layouts.app')

@section('title', 'Booking Detail')

@section('content')

    {{-- Header Section  --}}
    <section class="py-3">
        <div class="container mt-5">
            <div class="d-flex align-items-center justify-content-between">
                <div class="">
                    <h3 class="mb-1">Order Detail</h3>
                    <p>Order No : <span class="text-uppercase">{{ $booking->uuid ? $booking->uuid : 'N/A' }}</span></p>
                </div>
                <div class="">
                    <a href="#" class="btn btn-danger"><i class="fa fa-bug" aria-hidden="true"></i> <span
                            class="d-none d-md-inline"> Report an Issue</span></a>
                </div>
            </div>
    </section>


    {{-- Order Detail Section  --}}
    <section class="py-3">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    {{-- Order Summary --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="mb-0">Order Summary</h5>
                        </div>
                        <div class="card-body">
                            {{-- Priority --}}
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <p class="mb-0">Priority:</p>
                                <h6 class="mb-0">{{ $booking->prioritySetting->name }}</h6>
                            </div>
                            {{-- Vehicle --}}
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <p class="mb-0">Vehicle:</p>
                                <h6 class="mb-0">N/A</h6>
                            </div>
                            {{-- Package Value: --}}
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <p class="mb-0">Service Type:</p>
                                <h6 class="mb-0">{{ $booking->serviceType->name }}</h6>
                            </div>
                            {{-- Delivery Time: --}}
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <p class="mb-0">Pickup Address</p>
                                <h6 class="mb-0">{{ $booking->pickup_address }}</h6>
                            </div>
                            {{-- Receiver Details: --}}
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <p class="mb-0">Dropoff Address</p>
                                <h6 class="mb-0">{{ $booking->dropoff_address }}</h6>
                            </div>
                            {{-- Delivery Charges: --}}
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <p class="mb-0">Delivery Charges:</p>
                                <h6 class="mb-0">${{ $booking->prioritySetting->price }}</h6>
                            </div>
                            {{-- Amount to Pay --}}
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <p class="mb-0">Amount to Pay:</p>
                                <h6 class="mb-0">${{ $booking->total_price }}</h6>
                            </div>
                            {{-- Payment Method --}}
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <p class="mb-0">Payment Method:</p>
                                <h6 class="mb-0">{{ $booking->payment_method }}</h6>
                            </div>
                        </div>
                    </div>
                    {{-- Tracking Status --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="mb-0">Tracking Status</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    {{-- Progess Status --}}
                                    <div class="progressdiv">
                                        <ul class="progressbar p-0">
                                            <li {{ $booking->status == 'unpaid' ? 'class=active' : '' }}>
                                                <p>Unpaid</p>
                                            </li>
                                            <li {{ $booking->status == 'pending' ? 'class=active' : '' }}>
                                                <p>Pending</p>
                                            </li>
                                            <li {{ $booking->status == 'in_transit' ? 'class=active' : '' }}>
                                                <p>In Transit</p>
                                            </li>
                                            <li {{ $booking->status == 'delivered' ? 'class=active' : '' }}>
                                                <p>Delivered</p>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                {{-- Pickup --}}
                                <div class="text-center">
                                    <p class="mb-0">{{ $booking->booking_at }}</p>
                                    <h6 class="mb-0">New York</h6>
                                </div>
                                {{-- Drop off --}}
                                <div class="text-center">
                                    <p class="mb-0">Jan 30, 2024</p>
                                    <h6 class="mb-0">Washington</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    {{-- Map Tracking --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="mb-0">Tracking Order</h5>
                        </div>
                        <div class="card-body" style="height: 400px">
                            <div id="map" style="height:100%; width:100%;"></div>
                            <script
                                src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD-jXtk8qCpcwUwFn-7Q3VazeneJJ46g00&libraries=places&callback=initMap"
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
                    @if ($booking->helper_id)
                        {{-- Delivery Vehicle Detail  --}}
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="mb-0">Delivery Vehicle Detail</h5>
                            </div>
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                            <p class="mb-0">Vehicle type:</p>
                                            <h6 class="mb-0">Bike</h6>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                            <p class="mb-0">Make:</p>
                                            <h6 class="mb-0">Suzuki Motors</h6>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                            <p class="mb-0">Model:</p>
                                            <h6 class="mb-0">2018</h6>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                            <p class="mb-0">Number:</p>
                                            <h6 class="mb-0">RIL 123</h6>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="text-center">
                                            <img src="{{ asset('images/vehicles/bike.png') }}" alt="Truck"
                                                class="img-fluid">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>


@endsection
