@extends('frontend.layouts.app')

@section('title', 'New Booking')

@section('content')


    {{-- Define some javascript variables to be used in JS --}}
    <script>
        // Base URL
        const base_url = "{{ url('/') }}";

        // Define some javascript variables to be used in JS
        var csrf_token = "{{ csrf_token() }}";
        var distance_price = 0;
        var service_price = 0;
        var per_km_price = 0;
        var service_charges = 0;

        // Check Service Type
        var selectedServiceType = 'delivery';

        // Store $serviceCategories to JS array
        var selectedparceluuid = '';
        var selectedParcelTypeSecureshipEnable = false;
        var serviceCategories = {!! json_encode($serviceCategories) !!};
        if (serviceCategories.length > 0) {
            selectedparceluuid = serviceCategories[0].uuid;
            selectedParcelTypeSecureshipEnable = serviceCategories[0].is_secureship_enabled;
        }
        // Store $prioritySettings to JS array
        var prioritySettings = {!! json_encode($prioritySettings) !!};
        var selectedPriorityID = prioritySettings[0].id;
        console.log('Selected Priority ID: ' + selectedPriorityID);

        // Map Variables
        var map;
        var directionsService;
        var directionsRenderer;
        var defaultPickupLat = {{ request()->get('pickup_latitude', 33.33) }};
        var defaultPickupLng = {{ request()->get('pickup_longitude', 74.44) }};
        var defaultDeliveryLat = {{ request()->get('dropoff_latitude', 33.33) }};
        var defaultDeliveryLng = {{ request()->get('dropoff_longitude', 74.44) }};

        // Distance
        var distance = 0;
        var distance_in_km = 0;
        var distance_in_miles = 0;

        var shippingData = {
            "fromAddress": {
                "addr1": "1500 Bank St.",
                "countryCode": "CA",
                "postalCode": "K1K1K1",
                "city": "Ottawa",
                "taxId": "A‑123456‑Z",
                "residential": true,
                "isSaturday": true,
                "isInside": true,
                "isTailGate": true,
                "isTradeShow": true,
                "isLimitedAccess": true,
                "appointment": {
                    "appointmentType": "None",
                    "phone": "613-723-5891",
                    "date": "2023-08-19",
                    "time": "3:00 PM"
                }
            },
            "toAddress": {
                "addr1": "1500 Bank St.",
                "countryCode": "CA",
                "postalCode": "K1K1K1",
                "city": "Ottawa",
                "taxId": "A‑123456‑Z",
                "residential": true,
                "isSaturday": true,
                "isInside": true,
                "isTailGate": true,
                "isTradeShow": true,
                "isLimitedAccess": true,
                "appointment": {
                    "appointmentType": "None",
                    "phone": "613-723-5891",
                    "date": "2023-08-19",
                    "time": "3:00 PM"
                }
            },
            "packages": [{
                "packageType": "MyPackage",
                "userDefinedPackageType": "Refrigerator",
                "weight": 23,
                "weightUnits": "Lbs",
                "length": 19,
                "width": 230,
                "height": 430,
                "dimUnits": "Inches",
                "insurance": 18.3,
                "isAdditionalHandling": false,
                "signatureOptions": "None",
                "description": "Gift for darling",
                "temperatureProtection": true,
                "isDangerousGoods": true,
                "isNonStackable": true
            }],
            "shipDateTime": "2019-08-24T14:15:22Z",
            "currencyCode": "CAD",
            "billingOptions": "Prepaid",
            "isDocumentsOnly": true,
            "isStopinOnly": true
        };

        // Update the Payment Amount Card
        function updatePaymentAmount() {
            console.log('Distance: ' + distance_in_km);

            // If selectedparceluuid is empty
            if (selectedparceluuid == '') {
                // Get from first service type from serviceCategories
                selectedparceluuid = serviceCategories[0].uuid;
            }

            // Get data on selected uuid
            for (let i = 0; i < serviceCategories.length; i++) {
                if (serviceCategories[i].uuid === selectedparceluuid) {
                    // console.log(serviceCategories[i].base_price);
                    if (distance_in_km > parseFloat(serviceCategories[i].base_distance)) {
                        distance_price = parseFloat(serviceCategories[i].base_price) + (distance_in_km - parseFloat(
                            serviceCategories[i].base_distance)) * parseFloat(serviceCategories[i].extra_distance_price);
                    } else {
                        distance_price = parseFloat(serviceCategories[i].base_price);
                    }

                    service_price = 50;
                    vehicle_price = 100;
                }
            }

            // Get value of priority option
            var priorityID = document.querySelector('select[name="priority"]').value;
            // Get price of priority from prioritySettings
            for (let i = 0; i < prioritySettings.length; i++) {
                if (prioritySettings[i].id == priorityID) {
                    priorityValue = prioritySettings[i].price;
                }
            }

            // Calculate Weight Price Value
            calculateWeightPrice();

            var serviceType = document.querySelector('select[name="serviceType"]').value;
            document.getElementById('distance-price-value').innerHTML = Math.round(distance_price * 100) / 100;
            document.getElementById('service-price-value').innerHTML = Math.round(service_price * 100) / 100;
            document.getElementById('vehicle-price-value').innerHTML = Math.round(vehicle_price * 100) / 100;
            document.getElementById('priority-price-value').innerHTML = priorityValue;

            // Ge total amount
            var amountToPay = parseFloat(distance_price) +
                parseFloat(service_price) +
                parseFloat(priorityValue) +
                parseFloat(vehicle_price);
            document.getElementById('amount-to-pay-value').innerHTML = Math.round(amountToPay * 100) / 100;

            // console.log('Function calling ' + selectedparceluuid);
        }

        // Calculate Weight Price
        function calculateWeightPrice() {
            var weight = document.querySelector('input[name="package_weight"]').value;
            if (weight == '') {
                weight = 1;
            }
            var length = document.querySelector('input[name="package_length"]').value;
            if (length == '') {
                length = 1;
            }
            var width = document.querySelector('input[name="package_width"]').value;
            if (width == '') {
                width = 1;
            }
            var height = document.querySelector('input[name="package_height"]').value;
            if (height == '') {
                height = 1;
            }
            var cubicVolume = parseFloat(length) * parseFloat(width) * parseFloat(height);
            var cubicVolumeWeight = 0;
            var dimension = '{{ config('dimension') }}';

            if (dimension == 'INCH') {
                cubicVolumeWeight = cubicVolume / 1728;
            } else {
                cubicVolumeWeight = cubicVolume / 35.3147;
            }

            if (weight < cubicVolumeWeight) {
                document.getElementById('weight-price-value').innerHTML = Math.round((cubicVolumeWeight) * 100) /
                    100;
            } else {
                document.getElementById('weight-price-value').innerHTML = parseFloat(weight);
            }
        }
    </script>
    <div class="container py-5">
        {{-- Show Link to Draft Booking --}}
        @if (isset($draftBooking))
            <div class="bg-primary p-2 text-center text-white mb-3">
                <h5 class="m-0">You have a draft booking. <a class="text-white"
                        href="{{ route('client.booking.payment', $draftBooking->id) }}">Click
                        here</a> to pay.</h5>
            </div>
        @endif
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-md-12">
                <form class="booking-form" id="newBookingForm" onsubmit="return getEstimatedFees(event)">
                    <div class="heading text-center mb-5">
                        <h2 class="mb-1">Book with Us</h2>
                        <p>Please fill out the form below to get a quote for your shipment.</p>
                    </div>
                    {{-- Booking Form --}}
                    <div class="row">
                        <div class="col-md-4">
                            @csrf
                            {{-- Calculated Amount --}}
                            <div class="card mb-5">
                                <div class="card-header">
                                    <h5 class="mb-0">Payment Details</h5>
                                </div>
                                <div class="card-body flex-grow-1">
                                    <div class="calculated-amount">
                                        <div class="item">
                                            <h6>Distance Price</h6>
                                            <p>$<span id="distance-price-value">0</span></p>
                                        </div>
                                        <div class="item">
                                            <h6>Service Price</h6>
                                            <p>$<span id="service-price-value">0</span></p>
                                        </div>
                                        <div class="item">
                                            <h6>Priority Price</h6>
                                            <p>$<span id="priority-price-value">0</span></p>
                                        </div>
                                        <div class="item">
                                            <h6>Vehicle Price</h6>
                                            <p>$<span id="vehicle-price-value">0</span></p>
                                        </div>
                                        <div class="item moving d-none">
                                            <h6>Floor Price</h6>
                                            <p>$<span id="floor-price-value">0</span></p>
                                        </div>
                                        <div class="item delivery d-none">
                                            <h6>Weight Price</h6>
                                            <p>$<span id="weight-price-value">0</span></p>
                                        </div>
                                        <div class="item">
                                            <h6>Platform Charges</h6>
                                            <p>$<span id="platform-charge-value">0</span></p>
                                        </div>
                                        <hr>
                                        <div class="item">
                                            <h6>Amount to Pay</h6>
                                            <p>$<span id="amount-to-pay-value">45</span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Showing Map Here --}}
                            <div class="map-booking">
                                <div id="map" style="height: 400px; width:100%;"></div>
                                {{-- AIzaSyD-jXtk8qCpcwUwFn-7Q3VazeneJJ46g00 --}}
                                {{-- <script
                                    src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places&callback=initMap"
                                    async defer></script> --}}

                                <script
                                    src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places&callback=initMap"
                                    async defer></script>

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

                                        var pickupInput = document.getElementById('pickupLocation');
                                        var deliveryInput = document.getElementById('deliveryLocation');

                                        var pickupAutocomplete = new google.maps.places.Autocomplete(pickupInput);
                                        var deliveryAutocomplete = new google.maps.places.Autocomplete(deliveryInput);

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
                                                    shippingData.fromAddress.addr1 = result.formatted_address;
                                                    shippingData.fromAddress.countryCode = getAddressComponent(result, 'country');
                                                    shippingData.fromAddress.postalCode = getAddressComponent(result, 'postal_code');
                                                    shippingData.fromAddress.city = getAddressComponent(result, 'locality');
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
                                                    shippingData.toAddress.addr1 = result.formatted_address;
                                                    shippingData.toAddress.countryCode = getAddressComponent(result, 'country');
                                                    shippingData.toAddress.postalCode = getAddressComponent(result, 'postal_code');
                                                    shippingData.toAddress.city = getAddressComponent(result, 'locality');
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
                            </div>
                        </div>
                        <div class="col-md-8">
                            {{-- Showing text fields to add pickup and drop off --}}
                            <div class="row">
                                {{-- Pickup Location --}}
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="pickupLocation">Pickup Location</label>
                                        <input id="pickupLocation" class="form-control" type="text" name="pickup_address"
                                            placeholder="Enter pickup location"
                                            value="{{ request()->get('pickup_address') }}" required>
                                        <input type="hidden" id="pickup_latitude" name="pickup_latitude"
                                            value="{{ request()->get('pickup_latitude') }}" required>
                                        <input type="hidden" id="pickup_longitude" name="pickup_longitude"
                                            value="{{ request()->get('pickup_longitude') }}" required>
                                    </div>
                                </div>
                                {{-- Delivery Location --}}
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="deliveryLocation">Delivery Location</label>
                                        <input id="deliveryLocation" class="form-control" type="text"
                                            name="dropoff_address" placeholder="Enter delivery location"
                                            value="{{ request()->get('dropoff_address') }}" required>
                                        <input type="hidden" id="dropoff_latitude" name="dropoff_latitude"
                                            value="{{ request()->get('dropoff_latitude') }}" required>
                                        <input type="hidden" id="dropoff_longitude" name="dropoff_longitude"
                                            value="{{ request()->get('dropoff_longitude') }}" required>
                                    </div>
                                </div>
                                {{-- Service Type --}}
                                <div class="col-md-6">
                                    <label for="serviceType">Service Type</label>
                                    {{-- <select class="form-control" name="serviceType" id="serviceType" required> --}}
                                    <select class="form-control" name="serviceType" id="serviceType"
                                        onchange="parcelCategoriesDiv()" required>
                                        <option value="" disabled>Select Service</option>
                                        @if (!isset($serviceTypes))
                                            <option value="delivery">Delivery</option>
                                            {{-- <option value="moving" selected>Moving</option> --}}
                                        @else
                                            @foreach ($serviceTypes as $serviceType)
                                                {{-- Check if service type is selected --}}
                                                @if ($serviceType->id == request()->get('serviceType'))
                                                    <script>
                                                        selectedServiceType = '{{ $serviceType->type }}';
                                                    </script>
                                                @endif
                                                {{-- Select Option --}}
                                                <option value="{{ $serviceType->id }}"
                                                    {{ isset($serviceType) && $serviceType->id == request()->get('serviceType') ? 'selected' : '' }}>
                                                    {{ $serviceType->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                {{-- Priority --}}
                                <div class="col-md-6">
                                    <label for="priority">Priority</label>
                                    <div class="mb-3">
                                        <select class="form-control h-100" name="priority" aria-label="Priority"
                                            onchange="updatePaymentAmount()" required>
                                            {{-- Loop through prioritySettings --}}
                                            @foreach ($prioritySettings as $priority)
                                                {{-- Check if priority is selected --}}
                                                @if ($priority->id == request()->get('priority'))
                                                    <script>
                                                        selectedPriorityID = '{{ $priority->id }}';
                                                    </script>
                                                @endif
                                                <option value="{{ $priority->id }}"
                                                    {{ isset($priority) && $priority->price == request()->get('priority') ? 'selected' : '' }}>
                                                    {{ $priority->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                {{-- Parcel Types --}}
                                <div class="col-md-12">
                                    <label for="parcelType">Parcel Type</label>
                                    <div class="row h-50 parcels" id="parcelCategoriesDiv">
                                        @if (isset($serviceCategories))
                                            @foreach ($serviceCategories as $serviceCategory)
                                                <div class="col-md-4">
                                                    <div class="d-flex align-items-center cursor-pointer"
                                                        onclick="toggleBackground('{{ $serviceCategory->uuid }}')">
                                                        <div class="me-3">
                                                            <span class="form-check-input" style="display: none;">
                                                                <input type="radio" class="form-check-input"
                                                                    name="parcelType" value="{{ $serviceCategory->id }}"
                                                                    onclick="toggleBackground('{{ $serviceCategory->uuid }}')">
                                                            </span>
                                                        </div>
                                                        <div class="text-center parcel-type w-100"
                                                            id="{{ $serviceCategory->uuid }}">
                                                            {{-- <i class="fa fa-users fa-2x"></i> --}}
                                                            <h5 class="mb-1">{{ $serviceCategory->name }}</h5>
                                                            <p class="fs-xxs">{{ $serviceCategory->description }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                                {{-- Booking Date  --}}
                                <div class="col-md-6">
                                    <label for="bookingDate">Booking Date</label>
                                    <div class="mb-3">
                                        <input type="date" class="form-control" id="bookingDate" name="booking_date"
                                            value="<?php echo date('Y-m-d'); ?>" onchange="updatePaymentAmount()" required>
                                    </div>
                                </div>
                                {{-- Booking Time --}}
                                <div class="col-md-6">
                                    <label for="bookingTime">Booking Time</label>
                                    <div class="mb-3">
                                        <input type="time" class="form-control" id="bookingTime" name="booking_time"
                                            value="<?php echo date('H:i'); ?>" onchange="updatePaymentAmount()" required>
                                    </div>
                                </div>
                            </div>

                            {{-- Delivery Package Details --}}
                            <div id="deliveryPackageDetails" class="row d-none">
                                {{-- Package Length --}}
                                <div class="col-md-6">
                                    <label for="packageLength">Package Length</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" placeholder="Length"
                                            name="package_length" aria-describedby="package_length"
                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                            onchange="updatePaymentAmount()">
                                        <span class="input-group-text text-uppercase"
                                            id="package_length">{{ config('dimension') ?: 'INCH' }}</span>
                                    </div>
                                </div>
                                {{-- Package Width --}}
                                <div class="col-md-6">
                                    <label for="packageWidth">Package Width</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" placeholder="Width"
                                            name="package_width" aria-describedby="package_width"
                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                            onchange="updatePaymentAmount()">
                                        <span class="input-group-text text-uppercase"
                                            id="package_width">{{ config('dimension') ?: 'INCH' }}</span>
                                    </div>
                                </div>
                                {{-- Package Height  --}}
                                <div class="col-md-6">
                                    <label for="packageHeight">Package Height</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" placeholder="Height"
                                            name="package_height" aria-describedby="package_height"
                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                            onchange="updatePaymentAmount()">
                                        <span class="input-group-text text-uppercase"
                                            id="package_height">{{ config('dimension') ?: 'INCH' }}</span>
                                    </div>
                                </div>


                                {{-- Package Weight  --}}
                                <div class="col-md-6">
                                    <label for="packageWeight">Package Weight</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" placeholder="Weight"
                                            name="package_weight" aria-describedby="package_weight"
                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                            onchange="updatePaymentAmount()">
                                        <span class="input-group-text text-uppercase"
                                            id="package_weight">{{ config('weight') ?: 'Kg' }}</span>
                                    </div>
                                </div>
                                {{-- Check if package value decalared --}}
                                @if (config('declare_package_value') == 1)
                                    {{-- Package Value --}}
                                    <div class="col-md-6">
                                        <label for="packageValue">Package Value</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" placeholder="Value"
                                                name="package_value" aria-describedby="package_value"
                                                pattern="\d+(\.\d{0,2})?" inputmode="decimal"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/^(\d*\.?\d{0,2}).*$/g, '$1');"
                                                onchange="updatePaymentAmount()">
                                            <span class="input-group-text text-uppercase" id="package_value">$</span>
                                        </div>
                                    </div>
                                @endif

                                {{-- Check if insurance is enabled --}}
                                @if (config('insurance') == 1)
                                    {{-- Insurance --}}
                                    <div class="col-md-6">
                                        <label for="insurance">Insurance</label>
                                        <div class="input-group mb-3">
                                            <select class="form-control" name="insurance" aria-label="Insurance"
                                                onchange="updatePaymentAmount()">
                                                <option value="0">No</option>
                                                <option value="1">Yes</option>
                                            </select>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            {{-- Moving Package Details --}}
                            <div id="movingPackageDetails" class="row d-none">
                                {{-- Floor Size --}}
                                <div class="col-md-6">
                                    <label for="packageHeight">Floor Size</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control moving-field" placeholder="Floor Size"
                                            name="floor_size" aria-describedby="floor_size"
                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                            onchange="updatePaymentAmount()">
                                        <span class="input-group-text text-uppercase" id="floor_size">SQ FT</span>
                                    </div>
                                </div>
                                {{-- Floor Plan --}}
                                <div class="col-md-6">
                                    <label for="floorPlan">Floor Plan</label>
                                    <div class="input-group mb-3">
                                        <select class="form-control moving-field" name="floor_plan"
                                            aria-label="Floor Plan" onchange="updatePaymentAmount()">
                                            <option value="ground">Ground Floor</option>
                                            <option value="1st">1st Floor</option>
                                            <option value="2nd">2nd Floor</option>
                                            <option value="3rd">3rd Floor</option>
                                        </select>
                                    </div>
                                </div>
                                {{-- Floor Access --}}
                                <div class="col-md-6">
                                    <label for="floorAssess">Floor Access</label>
                                    <div class="input-group mb-3">
                                        <select class="form-control moving-field" name="floor_assess"
                                            aria-label="Floor Access" onchange="updatePaymentAmount()">
                                            <option value="elevator">Elevator</option>
                                            <option value="stairs">Stairs</option>
                                        </select>
                                    </div>
                                </div>
                                {{-- Job Details --}}
                                <div class="col-md-12">
                                    <label for="jobDetails">Job Details</label>
                                    <div class="row form-group mx-3 mb-3">
                                        <div class="col-md-4 form-check">
                                            <input class="form-check-input" type="checkbox" name="job_details[]"
                                                value="packing" id="packing">
                                            <label class="form-check-label" for="packing">
                                                Packing
                                            </label>
                                        </div>
                                        <div class="col-md-4 form-check">
                                            <input class="form-check-input" type="checkbox" name="job_details[]"
                                                value="loading" id="loading">
                                            <label class="form-check-label" for="loading">
                                                Loading
                                            </label>
                                        </div>
                                        <div class="col-md-4 form-check">
                                            <input class="form-check-input" type="checkbox" name="job_details[]"
                                                value="off_loading" id="off_loading">
                                            <label class="form-check-label" for="off_loading">
                                                Off Loading
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                {{-- Moving Details --}}
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="movingDetails">Moving Details</label>
                                        <textarea class="form-control moving-field" name="moving_details" id="movingDetails" rows="3"
                                            placeholder="Enter moving details"></textarea>
                                    </div>
                                </div>
                            </div>

                            {{-- Receiver Details --}}
                            <div class="row">
                                {{-- Receiver Name --}}
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="receiverName">Receiver Name</label>
                                        <input type="text" class="form-control" id="receiverName"
                                            name="receiver_name" placeholder="Enter receiver name">
                                    </div>
                                </div>
                                {{-- Receiver Email --}}
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="receiverEmail">Receiver Email</label>
                                        <input type="email" class="form-control" id="receiverEmail"
                                            name="receiver_email" placeholder="Enter receiver email">
                                    </div>
                                </div>
                                {{-- Receiver Phone --}}
                                {{-- <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="receiverPhone">Receiver Phone</label>
                                        <input type="text" class="form-control" id="receiverPhone"
                                            name="receiver_phone" placeholder="Enter receiver phone">
                                    </div>
                                </div> --}}
                                {{-- Delivery Note --}}
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="deliveryNote">Delivery Note</label>
                                        <textarea class="form-control" id="deliveryNote" name="delivery_note" rows="3"
                                            placeholder="Enter delivery note"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row">

                        @auth
                            <div class="col-md-12 text-right">
                                <button type="submit" class="btn btn-primary btn-block">Get
                                    Estimate</button>
                            </div>
                        @else
                            <div class="col-md-12 text-right">
                                <button type="button" class="btn btn-primary" onclick="redirectToLogin()">Login to
                                    Book</button>
                            </div>
                        @endauth
                    </div>
                </form>
            </div>
        </div>
    </div>


    {{-- Custom JS for Form Page --}}
    <script>
        // Parcel Type changes background color function
        function toggleBackground(id) {
            var divs = document.querySelectorAll('.parcel-type');
            divs.forEach(function(div) {
                if (div.id === id) {
                    div.classList.add('active-parcel');
                } else {
                    div.classList.remove('active-parcel');
                }
            });
            // console.log('Here id is:' + id);
            selectedparceluuid = id;
            // Call the function to update the payment amount
            updatePaymentAmount();
        }


        // Udpate the categories as per the service type selected



        // Function to call the API
        function getEstimatedFees() {

            // Check if selected service category is empty
            if (!selectedServiceType) {
                alert('Please select a service type');
                return;
            }
            // Check if selected parcel type is empty
            if (!selectedparceluuid) {
                alert('Please select a parcel type');
                return;
            }
            // Find the selected parcel type from serviceCategories
            var selectedParcelTypeSecureshipEnable = false;
            for (let i = 0; i < serviceCategories.length; i++) {
                if (serviceCategories[i].uuid == selectedparceluuid) {
                    selectedParcelTypeSecureshipEnable = serviceCategories[i].is_secureship_enabled;
                }
            }

            console.log(selectedParcelTypeSecureshipEnable);

            // Submit Form
            event.preventDefault();

            // Get all fields data from newBookingForm
            var newBookingForm = document.getElementById('newBookingForm');
            var formData = new FormData(newBookingForm);

            // Add additional fields
            formData.append('service_type_id', parseInt($("select[name='serviceType']").val()));
            formData.append('priority_setting_id', parseInt($("select[name='priority']").val()));
            formData.append('service_category_id', selectedparceluuid);
            formData.append('total_price', $("#amount-to-pay-value").text());
            formData.append('booking_type', selectedServiceType);

            // Remove some data
            // formData.delete('serviceType');
            // formData.delete('priority');

            // Stringify the form data
            // formData = JSON.stringify(Object.fromEntries(formData));

            console.log(formData);

            // Append csrf token
            // formData.append('_token', '{{ csrf_token() }}');

            // console.log(formData);

            let base_url = '{{ url('/') }}';

            // POST AJAX Call to /client/booking/store

            $.ajax({
                url: base_url + '/client/booking/store',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    console.log(response);
                    if (response.success == true) {
                        window.location.href = base_url + '/client/booking/payment/' + response.data.id;
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });



            return false;

        }

        // Update the form data as per the service type
        function updateServiceFormData() {
            // console.log(selectedServiceType);
            if (selectedServiceType == 'moving') {
                // Hide and Show Div
                $("#deliveryPackageDetails").addClass("d-none");
                $("#movingPackageDetails").removeClass("d-none");

                // Add and Remove required attribute
                $("#movingPackageDetails input").prop("required", true);
                $("#deliveryPackageDetails input").prop("required", false);

                // Hide and Show Prices
                $(".calculated-amount .moving").removeClass("d-none");
                $(".calculated-amount .delivery").addClass("d-none");
            } else {
                // Hide and Show Div
                $("#deliveryPackageDetails").removeClass("d-none");
                $("#movingPackageDetails").addClass("d-none");

                // Add and Remove required attribute
                $("#movingPackageDetails input").prop("required", false);
                $("#deliveryPackageDetails input").prop("required", true);

                // Hide and Show Prices
                $(".calculated-amount .moving").addClass("d-none");
                $(".calculated-amount .delivery").removeClass("d-none");
            }
        }


        // Call window.onload function
        window.onload = function() {
            // Call the function
            updateServiceFormData();
            toggleBackground(selectedparceluuid);
        }
        // Update the payment card
        // updatePaymentAmount();
        // Select the selected parcel uuid

        // Redirect to login page
        function redirectToLogin() {
            // Store form data in local storage
            // storeFormDataLocalStorage();
            window.location.href = "{{ route('client.login') }}";
        }

        // Function to store form data in local storage
        function storeFormDataLocalStorage() {
            console.log('Function Called storeFormDataLocalStorage');
            // localStorage.setItem('selectedServiceType', selectedServiceType);
            // console.log('Selected Service Type in Local Storage: ' + localStorage.getItem('selectedServiceType'));
        }
    </script>

@endsection
