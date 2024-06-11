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
        <div id="map" style="height: 500px;"></div>

        <script
            src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places&callback=initMap"
            async defer></script>

        <script>
            var map;
            var service;
            var infowindow;

            // Get lat long from $booking
            var defaultPickupLat = parseFloat(
                '{{ isset($booking->pickup_latitude) && $booking->pickup_latitude ? $booking->pickup_latitude : 33.6115 }}');
            var defaultPickupLng = parseFloat(
                '{{ isset($booking->pickup_longitude) && $booking->pickup_longitude ? $booking->pickup_longitude : 72.9706 }}'
            );
            var defaultDeliveryLat = parseFloat(
                '{{ isset($booking->dropoff_latitude) && $booking->dropoff_latitude ? $booking->dropoff_latitude : 33.6115 }}'
            );
            var defaultDeliveryLng = parseFloat(
                '{{ isset($booking->dropoff_longitude) && $booking->dropoff_longitude ? $booking->dropoff_longitude : 72.9706 }}'
            );

            function initMap() {
                map = new google.maps.Map(document.getElementById('map'), {
                    center: {
                        lat: defaultPickupLat,
                        lng: defaultPickupLng
                    },
                    zoom: 12
                });

                infowindow = new google.maps.InfoWindow;

                // Create markers for pickup and delivery locations
                var pickupMarker = new google.maps.Marker({
                    position: {
                        lat: defaultPickupLat,
                        lng: defaultPickupLng
                    },
                    map: map,
                    title: 'Pickup Location'
                });

                var deliveryMarker = new google.maps.Marker({
                    position: {
                        lat: defaultDeliveryLat,
                        lng: defaultDeliveryLng
                    },
                    map: map,
                    title: 'Delivery Location'
                });

                // Define the path for the polyline
                var pathCoordinates = [{
                        lat: defaultPickupLat,
                        lng: defaultPickupLng
                    },
                    {
                        lat: defaultDeliveryLat,
                        lng: defaultDeliveryLng
                    }
                ];

                // Create the polyline
                var polyline = new google.maps.Polyline({
                    path: pathCoordinates,
                    geodesic: true,
                    strokeColor: '#038164',
                    strokeOpacity: 1.0,
                    strokeWeight: 5
                });

                // Set the polyline on the map
                polyline.setMap(map);
            }

            function callback(results, status) {
                if (status == google.maps.places.PlacesServiceStatus.OK) {
                    for (var i = 0; i < results.length; i++) {
                        createMarker(results[i]);
                    }
                }
            }

            function createMarker(place) {
                var placeLoc = place.geometry.location;
                var marker = new google.maps.Marker({
                    map: map,
                    position: place.geometry.location
                });

                google.maps.event.addListener(marker, 'click', function() {
                    infowindow.setContent(place.name);
                    infowindow.open(map, this);
                });
            }

            document.addEventListener('DOMContentLoaded', function() {
                initMap();
            });


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

    </div>


@endsection
