@extends('client.layouts.app')

@section('title', 'Track Order')

@section('content')

    <div class="container mb-5 p-3">
        <h5>Track Order</h5>
        <div class="row">
            <div class="col-md-9">
                <input class="form-control" type="text" name="order_id" id="order_id" placeholder="Enter order id" required>
            </div>
            <div class="col-md-3">
                <button class="btn btn-primary w-100" onclick="trackOrder()">Track</button>
            </div>
        </div>
        <div id="map" style="height: 500px;"></div>

        <script>
            var map;
            var service;
            var infowindow;

            function initMap() {
                map = new google.maps.Map(document.getElementById('map'), {
                    center: {
                        lat: -34.397,
                        lng: 150.644
                    },
                    zoom: 6
                });
                infowindow = new google.maps.InfoWindow;
                document.getElementById('submit').addEventListener('click', function() {
                    trackOrder();
                });
            }

            function trackOrder() {
                var order_id = document.getElementById("order_id").value;

                // if empty show error
                if (!order_id) {
                    alert('Please fill up order id');
                    return false;
                }

                service = new google.maps.places.PlacesService(map);
                service.textSearch({
                    query: order_id
                }, callback);
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
        </script>
        <script
            src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places&callback=initMap"
            async defer></script>

    </div>


@endsection
