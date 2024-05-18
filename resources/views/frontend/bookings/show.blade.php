@extends('frontend.layouts.app')

@section('title', 'Booking Detail')

@section('content')

    {{-- Header Section  --}}
    <section class="py-3">
        <div class="container mt-5">
            <div class="d-flex align-items-center justify-content-between">
                <div class="">
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
                    {{-- Tracking Status --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="mb-0">Tracking Status</h5>
                        </div>
                        <div class="card-body">
                            {{-- Order Status --}}
                            <div class="progressdiv">
                                <ul class="progressbar p-0">
                                    {{-- Pending --}}
                                    <li {{ $booking->status == 'pending' ? 'class=active' : '' }}>
                                        <div class="d-flex">
                                            <div class="text-right">
                                                <h5>{{ $bookingDelivery->created_at ? app('dateHelper')->formatTimestamp($bookingDelivery->created_at, 'Y-m-d') : 'Expected' }}
                                                </h5>
                                                <p>{{ $bookingDelivery->created_at ? app('dateHelper')->formatTimestamp($bookingDelivery->created_at, 'H:i') : '-' }}
                                                </p>
                                            </div>
                                            <div class="circle mx-3">
                                            </div>
                                        </div>
                                        <div class="">
                                            <h6>Order Booked</h6>
                                            <p>6391 Washington</p>
                                        </div>
                                    </li>
                                    {{-- Accepted --}}
                                    <li {{ $booking->status == 'accepted' ? 'class=active' : '' }}>
                                        <div class="d-flex">
                                            <div class="text-right">
                                                <h5>{{ $bookingDelivery->accepted_at ? app('dateHelper')->formatTimestamp($bookingDelivery->accepted_at, 'Y-m-d') : 'Expected' }}
                                                </h5>
                                                <p>{{ $bookingDelivery->accepted_at ? app('dateHelper')->formatTimestamp($bookingDelivery->accepted_at, 'H:i') : '-' }}
                                                </p>
                                            </div>
                                            <div class="circle mx-3">
                                            </div>
                                        </div>
                                        <div class="">
                                            <h6>Order Assigned</h6>
                                            <p>6391 Washington</p>
                                        </div>
                                    </li>
                                    {{-- Picked Up --}}
                                    <li {{ $booking->status == 'started' ? 'class=active' : '' }}>
                                        <div class="d-flex">
                                            <div class="text-right">
                                                <h5>{{ $bookingDelivery->start_booking_at ? app('dateHelper')->formatTimestamp($bookingDelivery->start_booking_at, 'Y-m-d') : 'Expected' }}
                                                </h5>
                                                <p>{{ $bookingDelivery->start_booking_at ? app('dateHelper')->formatTimestamp($bookingDelivery->start_booking_at, 'H:i') : '-' }}
                                                </p>
                                            </div>
                                            <div class="circle mx-3">
                                            </div>
                                        </div>
                                        <div class="">
                                            <h6>Package Received</h6>
                                            <p>6391 Washington</p>
                                        </div>
                                    </li>
                                    {{-- Delivered --}}
                                    <li {{ $booking->status == 'in_transit' ? 'class=active' : '' }}>
                                        <div class="d-flex">
                                            <div class="text-right">
                                                <h5>{{ $bookingDelivery->start_intransit_at ? app('dateHelper')->formatTimestamp($bookingDelivery->start_intransit_at, 'Y-m-d') : 'Expected' }}
                                                </h5>
                                                <p>{{ $bookingDelivery->start_intransit_at ? app('dateHelper')->formatTimestamp($bookingDelivery->start_intransit_at, 'H:i') : '-' }}
                                                </p>
                                            </div>
                                            <div class="circle mx-3">
                                            </div>
                                        </div>
                                        <div class="">
                                            <h6>Delivering</h6>
                                            <p>6391 Washington</p>
                                        </div>
                                    </li>
                                    <li {{ $booking->status == 'completed' ? 'class=active' : '' }}>
                                        <div class="d-flex">
                                            <div class="text-right">
                                                <h5>{{ $bookingDelivery->complete_booking_at ? app('dateHelper')->formatTimestamp($bookingDelivery->complete_booking_at, 'Y-m-d') : 'Expected' }}
                                                </h5>
                                                <p>{{ $bookingDelivery->complete_booking_at ? app('dateHelper')->formatTimestamp($bookingDelivery->complete_booking_at, 'H:i') : '-' }}
                                                </p>
                                            </div>
                                            <div class="circle mx-3">
                                            </div>
                                        </div>
                                        <div class="">
                                            <h6>Receipent Received</h6>
                                            <p>6391 Washington</p>
                                        </div>
                                    </li>
                                </ul>
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
