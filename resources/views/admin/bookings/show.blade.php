@extends('admin.layouts.app')

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
                                        <div class="text-right">
                                            <h6>05 May 2024</h6>
                                            <p>11:00 AM</p>
                                        </div>
                                        <div class="circle mx-3">
                                        </div>
                                        <div class="">
                                            <h6>Parcel Booked</h6>
                                            <p>6391 Washington</p>
                                        </div>
                                    </li>
                                    {{-- Accepted --}}
                                    <li {{ $booking->status == 'accepted' ? 'class=active' : '' }}>
                                        <div class="text-right">
                                            <h6>07 May 2024</h6>
                                            <p>10:00</p>
                                        </div>
                                        <div class="circle mx-3">
                                        </div>
                                        <div class="">
                                            <h6>Parcel Received</h6>
                                            <p>6391 Washington</p>
                                        </div>
                                    </li>
                                    {{-- Picked Up --}}
                                    <li {{ $booking->status == 'picked' ? 'class=active' : '' }}>
                                        <div class="text-right">
                                            <h6>Expected</h6>
                                            <p>10:00 PM</p>
                                        </div>
                                        <div class="circle mx-3">
                                        </div>
                                        <div class="">
                                            <h6>Driver will pick up</h6>
                                            <p>6391 Washington</p>
                                        </div>
                                    </li>
                                    {{-- Delivered --}}
                                    <li {{ $booking->status == 'delivered' ? 'class=active' : '' }}>
                                        <div class="text-right">
                                            <h6>Expected</h6>
                                            <p>11:00 PM</p>
                                        </div>
                                        <div class="circle mx-3">
                                        </div>
                                        <div class="">
                                            <h6>Driver will deliver</h6>
                                            <p>6391 Washington</p>
                                        </div>
                                    </li>
                                    <li {{ $booking->status == 'completed' ? 'class=active' : '' }}>
                                        <div class="text-right">
                                            <h6>Expected</h6>
                                            <p>11:00</p>
                                        </div>
                                        <div class="circle mx-3">
                                        </div>
                                        <div class="">
                                            <h6>Order will complete</h6>
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
                            {{-- Include Order Summary from admin\bookings\partials\info.blade.php --}}
                            @include('admin.bookings.partials.info')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


@endsection
