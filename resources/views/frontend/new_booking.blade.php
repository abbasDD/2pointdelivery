@extends('frontend.layouts.app')

@section('title', 'Booking')

@section('content')
    {{-- Define some javascript variables to be used in JS --}}
    <script>
        // Base URL
        const base_url = "{{ url('/') }}";

        // Map Variables
        var map;
        var directionsService;
        var directionsRenderer;
        var defaultPickupLat = {{ request()->get('pickup_lat', 33.33) }};
        var defaultPickupLng = {{ request()->get('pickup_lng', 74.44) }};
        var defaultDeliveryLat = {{ request()->get('delivery_lat', 33.33) }};
        var defaultDeliveryLng = {{ request()->get('delivery_lng', 74.44) }};

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
    </script>
    <div class="container py-5">
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
                            {{-- Calculated Amount --}}
                            <div class="card mb-5">
                                <div class="card-header">
                                    <h5 class="mb-0">Payment Details</h5>
                                </div>
                                <div class="card-body flex-grow-1">
                                    <div class="calculated-amount">
                                        <div class="item">
                                            <h6>Distance Price</h6>
                                            <p>$25</p>
                                        </div>
                                        <div class="item">
                                            <h6>Service Price</h6>
                                            <p>$5</p>
                                        </div>
                                        <div class="item">
                                            <h6>Vehicle Price</h6>
                                            <p>$15</p>
                                        </div>
                                        <div class="item">
                                            <h6>Floor Price</h6>
                                            <p>$0</p>
                                        </div>
                                        <div class="item">
                                            <h6>Service Charges</h6>
                                            <p>$0</p>
                                        </div>
                                        <div class="item">
                                            <h6>Platform Charges</h6>
                                            <p>$0</p>
                                        </div>
                                        <hr>
                                        <div class="item">
                                            <h6>Amount to Pay</h6>
                                            <p>$45</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Showing Map Here --}}
                            <div class="map-booking">
                                <div id="map" style="height: 400px; width:100%;"></div>
                                <script
                                    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD-jXtk8qCpcwUwFn-7Q3VazeneJJ46g00&libraries=places&callback=initMap"
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
                                            document.getElementById('pickup_lat').value = place.geometry.location.lat();
                                            document.getElementById('pickup_lng').value = place.geometry.location.lng();
                                            updateRoute();
                                        });

                                        deliveryAutocomplete.addListener('place_changed', function() {
                                            var place = deliveryAutocomplete.getPlace();
                                            document.getElementById('delivery_lat').value = place.geometry.location.lat();
                                            document.getElementById('delivery_lng').value = place.geometry.location.lng();
                                            updateRoute();
                                        });
                                    }

                                    function updateRoute() {
                                        var pickupLat = parseFloat(document.getElementById('pickup_lat').value || defaultPickupLat);
                                        var pickupLng = parseFloat(document.getElementById('pickup_lng').value || defaultPickupLng);
                                        var deliveryLat = parseFloat(document.getElementById('delivery_lat').value || defaultDeliveryLat);
                                        var deliveryLng = parseFloat(document.getElementById('delivery_lng').value || defaultDeliveryLng);

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
                                                    console.log(results[0]);
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
                                                    console.log(results[0].formatted_address);
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
                                        <input id="pickupLocation" class="form-control" type="text"
                                            name="pickup_location" placeholder="Enter pickup location"
                                            value="{{ request()->get('pickup_location') }}" required>
                                        <input type="hidden" id="pickup_lat" name="pickup_lat"
                                            value="{{ request()->get('pickup_lat') }}" required>
                                        <input type="hidden" id="pickup_lng" name="pickup_lng"
                                            value="{{ request()->get('pickup_lng') }}" required>
                                    </div>
                                </div>
                                {{-- Delivery Location --}}
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="deliveryLocation">Delivery Location</label>
                                        <input id="deliveryLocation" class="form-control" type="text"
                                            name="delivery_location" placeholder="Enter delivery location"
                                            value="{{ request()->get('delivery_location') }}" required>
                                        <input type="hidden" id="delivery_lat" name="delivery_lat"
                                            value="{{ request()->get('delivery_lat') }}" required>
                                        <input type="hidden" id="delivery_lng" name="delivery_lng"
                                            value="{{ request()->get('delivery_lng') }}" required>
                                    </div>
                                </div>
                                {{-- Service Type --}}
                                <div class="col-md-6">
                                    <label for="serviceType">Service Type</label>
                                    <select class="form-control" name="serviceType" id="serviceType" required>
                                        {{-- <select class="form-control" name="serviceType" id="serviceType"
                                        onchange="parcelCategoriesDiv()" required> --}}
                                        <option value="" disabled>Select Service</option>
                                        @if (!isset($serviceTypes))
                                            <option value="delivery">Delivery</option>
                                            {{-- <option value="moving" selected>Moving</option> --}}
                                        @else
                                            @foreach ($serviceTypes as $serviceType)
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
                                        <select class="form-control h-100" name="priority" aria-label="Priority" required>
                                            <option value="express">Express</option>
                                            <option value="same_day">Same Day</option>
                                            <option value="standard">Standard</option>
                                        </select>
                                    </div>
                                </div>
                                {{-- Parcel Types --}}
                                {{-- <div class="col-md-12">
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
                                                        <div class="text-center parcel-type"
                                                            id="{{ $serviceCategory->uuid }}">
                                                            <i class="fa fa-users fa-2x"></i>
                                                            <h5 class="mb-1">{{ $serviceCategory->name }}</h5>
                                                            <p class="fs-xxs">{{ $serviceCategory->description }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div> --}}
                                {{-- Booking Date  --}}
                                <div class="col-md-6">
                                    <label for="bookingDate">Booking Date</label>
                                    <div class="mb-3">
                                        <input type="date" class="form-control" id="bookingDate" name="booking_date"
                                            value="<?php echo date('Y-m-d'); ?>" required>
                                    </div>
                                </div>
                                {{-- Booking Time --}}
                                <div class="col-md-6">
                                    <label for="bookingTime">Booking Time</label>
                                    <div class="mb-3">
                                        <input type="time" class="form-control" id="bookingTime" name="booking_time"
                                            value="<?php echo date('H:i'); ?>" required>
                                    </div>
                                </div>
                                {{-- Package Height  --}}
                                <div class="col-md-6">
                                    <label for="packageHeight">Package Height</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" placeholder="Height"
                                            name="package_height" aria-describedby="package_height"
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                                        <span class="input-group-text" id="package_height">INCH</span>
                                    </div>
                                </div>
                                {{-- Package Width --}}
                                <div class="col-md-6">
                                    <label for="packageWidth">Package Width</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" placeholder="Width"
                                            name="package_width" aria-describedby="package_width"
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                                        <span class="input-group-text" id="package_width">INCH</span>
                                    </div>
                                </div>
                                {{-- Package Weight  --}}
                                <div class="col-md-6">
                                    <label for="packageWeight">Package Weight</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" placeholder="Weight"
                                            name="package_weight" aria-describedby="package_weight"
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                        <span class="input-group-text" id="package_weight">KG</span>
                                    </div>
                                </div>
                                {{-- Package Value --}}
                                <div class="col-md-6">
                                    <label for="packageValue">Package Value</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" placeholder="Value"
                                            name="package_value" aria-describedby="package_value"
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                                        <span class="input-group-text" id="package_value">$</span>
                                    </div>
                                </div>
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
                        <div class="col-md-12 text-right">
                            <button type="submit" class="btn btn-primary btn-block">Get
                                Estimate</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


    {{-- Custom JS for Form Page --}}
    <script>
        // // Parcel Type changes background color function
        // function toggleBackground(id) {
        //     var divs = document.querySelectorAll('.parcel-type');
        //     divs.forEach(function(div) {
        //         if (div.id === id) {
        //             div.classList.add('active-parcel');
        //         } else {
        //             div.classList.remove('active-parcel');
        //         }
        //     });
        //     // console.log(id);
        // }


        // // Udpate the categories as per the service type selected

        // function parcelCategoriesDiv() {
        //     // console.log('Function Called');
        //     var serviceType = document.querySelector('select[name="serviceType"]').value;
        //     // console.log(serviceType);
        //     var url =
        //         '{{ route('fetch.service.categories') }}' +
        //         '?serviceType=' + serviceType; // Replace 'fetch.service.categories' with your actual route name
        //     // var formData = new FormData();
        //     // formData.append('serviceType', serviceType);

        //     fetch(url, {
        //             method: 'GET'
        //         })
        //         .then(response => response.json())
        //         .then(data => {
        //             // Update parcel categories div based on received data
        //             var parcelCategoriesDiv = document.getElementById('parcelCategoriesDiv');
        //             parcelCategoriesDiv.innerHTML = ''; // Clear previous content
        //             data.forEach(category => {
        //                 var categoryDiv = document.createElement('div');
        //                 categoryDiv.classList.add('col-md-4');
        //                 categoryDiv.innerHTML = `
    //             <div class="d-flex align-items-center cursor-pointer" onclick="toggleBackground('${category.uuid}')">
    //                 <div class="me-3">
    //                     <span class="form-check-input" style="display: none;">
    //                         <input type="radio" class="form-check-input" name="parcelType"
    //                             value="${category.uuid}">
    //                     </span>
    //                 </div>
    //                 <div class="text-center parcel-type"
    //                     id="${category.uuid}">
    //                     <i class="fa fa-users fa-2x"></i>
    //                     <h5 class="mb-1">${category.name}</h5>
    //                     <p class="fs-xxs">${category.description}</p>
    //                 </div>
    //             </div>
    //         `;
        //                 parcelCategoriesDiv.appendChild(categoryDiv);
        //             });
        //         })
        //         .catch(error => {
        //             console.error('Error:', error);
        //         });
        // }



        // Get estimate from https://secureship.ca/ship/api/docs#tag/Carriers/operation/Carriers_CalculateRates
        var apiKey = '0226b62a-f112-4d22-a8fc-d05b67a38e26'; // Replace 'YOUR_API_KEY' with your actual API key

        // Function to call the API
        function getEstimatedFees() {

            event.preventDefault(); // Prevent the default form submission behavior

            var apiUrl = 'https://secureship.ca/ship/api/v1/carriers/rates';
            // var apiUrl =
            //     'https://secureship.ca/ship/connect/query-string/get-estimate?FromCC=CA&FromPC=k1k1k1&FromCity=Ottawa&ToCC=US&ToPC=90210&ToCity=Beverly%20Hills&PT1=MyPackage&Weight1=5&PT2=MyPackage&Weight2=6&L2=4&W2=6&H2=8&Debug=true';
            // console.log('Function Called');

            // Make an AJAX POST request to the API
            fetch(apiUrl, {
                    // method: 'GET',
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'x-api-key': apiKey
                    },
                    body: JSON.stringify(shippingData)
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(json => {
                    console.log('API response:', json);
                    if (json.length > 0) {
                        alert('Estimated Fees: ');
                    } else {
                        alert('No rates found');
                    }
                    // Handle the API response as needed
                })
                .catch(error => {
                    console.error('There was a problem with the API request:', error.message);
                    // Handle errors
                });
        }
    </script>

@endsection
