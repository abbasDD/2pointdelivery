<script>
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

        var pickupInput = document.getElementById('pickup_address');
        var deliveryInput = document.getElementById('dropoff_address');

        var options = {
            componentRestrictions: {
                country: ["ca"]
            }
        };

        var pickupAutocomplete = new google.maps.places.Autocomplete(pickupInput, options);
        var deliveryAutocomplete = new google.maps.places.Autocomplete(deliveryInput, options);

        pickupAutocomplete.addListener('place_changed', function() {
            var place = pickupAutocomplete.getPlace();
            document.getElementById('pickup_latitude').value = place.geometry.location.lat();
            document.getElementById('pickup_longitude').value = place.geometry.location.lng();
            updateRoute();
        });

        deliveryAutocomplete.addListener('place_changed', function() {
            var place = deliveryAutocomplete.getPlace();
            document.getElementById('dropoff_latitude').value = place.geometry.location.lat();
            document.getElementById('dropoff_longitude').value = place.geometry.location.lng();
            updateRoute();
        });
    }

    function updateRoute() {
        var pickupLat = parseFloat(document.getElementById('pickup_latitude').value || defaultPickupLat);
        var pickupLng = parseFloat(document.getElementById('pickup_longitude').value || defaultPickupLng);
        var deliveryLat = parseFloat(document.getElementById('dropoff_latitude').value || defaultDeliveryLat);
        var deliveryLng = parseFloat(document.getElementById('dropoff_longitude').value || defaultDeliveryLng);

        var pickup = new google.maps.LatLng(pickupLat, pickupLng);
        var delivery = new google.maps.LatLng(deliveryLat, deliveryLng);
        //Distance between pickup and delivery in km and miles
        var distanceService = new google.maps.DistanceMatrixService();
        distanceService.getDistanceMatrix({
            origins: [pickup],
            destinations: [delivery],
            travelMode: google.maps.TravelMode.DRIVING,
            unitSystem: google.maps.UnitSystem.METRIC,
        }, function(response, status) {
            if (status === 'OK') {
                distance = response.rows[0].elements[0].distance.text;
                distance_in_km = response.rows[0].elements[0].distance.value / 1000;
                distance_in_miles = (response.rows[0].elements[0].distance.value / 1000) * 0.621371;
                // console.log(distance);
                // console.log(distance_in_km);
                // console.log(distance_in_miles);

                // Update payment amount
                updatePaymentAmount();

                // document.getElementById('distance').value = distance;
                // document.getElementById('distance_in_km').value = distance_in_km.toFixed(2);
                // document.getElementById('distance_in_miles').value = distance_in_miles.toFixed(2);
            }
        });

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

        // Store address of pickup to shippingData
        var geocoder = new google.maps.Geocoder();
        var latlng = new google.maps.LatLng(pickupLat, pickupLng);
        geocoder.geocode({
            'location': latlng
        }, function(results, status) {
            if (status === google.maps.GeocoderStatus.OK) {
                if (results[0]) {
                    // callback(results[0]);
                    // console.log(results[0]);
                    result = results[0];
                } else {
                    console.error('No address found for the provided coordinates.');
                    // callback(null);
                }
            } else {
                console.error('Geocoder failed due to: ' + status);
                // callback(null);
            }
            // console.log(shippingData);
        });

        // Store address of pickup to shippingData
        var geocoder = new google.maps.Geocoder();
        var latlng = new google.maps.LatLng(deliveryLat, deliveryLng);
        geocoder.geocode({
            'location': latlng
        }, function(results, status) {
            if (status === google.maps.GeocoderStatus.OK) {
                if (results[0]) {
                    // callback(results[0]);
                    // console.log(results[0].formatted_address);
                    result = results[0];
                } else {
                    console.error('No address found for the provided coordinates.');
                    // callback(null);
                }
            } else {
                console.error('Geocoder failed due to: ' + status);
                // callback(null);
            }
            // console.log(shippingData);

        });

        // console.log(shippingData);

    }

    // Helper function to get address component from geocode result
    function getAddressComponent(result, type) {
        for (var i = 0; i < result.address_components.length; i++) {
            var component = result.address_components[i];
            for (var j = 0; j < component.types.length; j++) {
                if (component.types[j] === type) {
                    return component.short_name;
                }
            }
        }
        return '';
    }
</script>
