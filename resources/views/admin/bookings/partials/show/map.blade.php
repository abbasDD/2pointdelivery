<div class="card mb-3">
    <div class="card-header">
        <h5 class="mb-0">Tracking Order</h5>
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