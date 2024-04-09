@extends('frontend.layouts.app')

@section('title', 'Booking')

@section('content')

    <div class="container py-5">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-md-12">
                <form class="booking-form" id="newBookingForm">
                    <div class="heading text-center">
                        <h2 class="mb-1">Book with Us</h2>
                        <p>Please fill out the form below to get a quote for your shipment.</p>
                    </div>
                    <div class="all-steps" id="all-steps"> <span class="step"></span> <span class="step"></span> <span
                            class="step"></span> <span class="step"></span> </div>
                    <div class="tab">
                        <div class="row">
                            {{-- Calculated Amount --}}
                            <div class="col-md-4 d-flex flex-column">
                                <div class="card h-100">
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
                            </div>
                            <div class="col-md-8 position-relative d-flex flex-column">
                                {{-- Showing Map Here --}}
                                <div class="map-booking flex-grow-1">
                                    <div id="map" style="height:100%; width:100%;"></div>
                                    <script
                                        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD-jXtk8qCpcwUwFn-7Q3VazeneJJ46g00&libraries=places&callback=initMap"
                                        async defer></script>
                                    <script>
                                        var map;
                                        var directionsService;
                                        var directionsRenderer;
                                        var defaultPickupLat = {{ request()->get('pickup_lat', 33.33) }};
                                        var defaultPickupLng = {{ request()->get('pickup_lng', 74.44) }};
                                        var defaultDeliveryLat = {{ request()->get('delivery_lat', 33.33) }};
                                        var defaultDeliveryLng = {{ request()->get('delivery_lng', 74.44) }};

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
                                {{-- Showing text fields to add pickup and drop off --}}
                                <div class="row location-div flex-grow-1">
                                    <div class="col-md-5">
                                        <div class="mb-3">
                                            <input id="pickupLocation" class="form-control" type="text"
                                                name="pickup_location" placeholder="Enter pickup location"
                                                value="{{ request()->get('pickup_location') }}" required>
                                            <input type="hidden" id="pickup_lat" name="pickup_lat"
                                                value="{{ request()->get('pickup_lat') }}" required>
                                            <input type="hidden" id="pickup_lng" name="pickup_lng"
                                                value="{{ request()->get('pickup_lng') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="mb-3">
                                            <input id="deliveryLocation" class="form-control" type="text"
                                                name="delivery_location" placeholder="Enter delivery location"
                                                value="{{ request()->get('delivery_location') }}" required>
                                            <input type="hidden" id="delivery_lat" name="delivery_lat"
                                                value="{{ request()->get('delivery_lat') }}" required>
                                            <input type="hidden" id="delivery_lng" name="delivery_lng"
                                                value="{{ request()->get('delivery_lng') }}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>

                        {{-- Ask User to select prioirty and service type --}}
                        <div class="row pt-3">
                            <div class="col-md-4 h-100 d-flex flex-column">
                                <div class="h-50">
                                    {{-- Service Type --}}
                                    <div class="">
                                        <label for="serviceType">Service Type</label>
                                        <div class="mb-3">
                                            <select class="form-select h-100" aria-label="Service Type" name="serviceType">
                                                <option value="delivery">Delivery</option>
                                                <option value="moving">Moving</option>
                                            </select>
                                        </div>
                                    </div>
                                    {{-- Priority --}}
                                    <div class="">
                                        <label for="priority">Priority</label>
                                        <div class="mb-3">
                                            <select class="form-select h-100" aria-label="Priority">
                                                <option value="express">Express</option>
                                                <option value="same_day">Same Day</option>
                                                <option value="standard" selected>Standard</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8 h-100 d-flex flex-column justify-content-center">
                                {{-- Parcel Types --}}
                                <label for="parcelType">Parcel Type</label>
                                <div class="row h-50 parcels">
                                    <div class="col-md-4">
                                        <div class="d-flex align-items-center cursor-pointer"
                                            onclick="document.querySelector('input[name=parcelType][value=small]').click()">
                                            <div class="me-3">
                                                <span class="form-check-input" style="display: none;">
                                                    <input type="radio" class="form-check-input" name="parcelType"
                                                        value="small" onclick="toggleBackground('small')">
                                                </span>
                                            </div>
                                            <div class="text-center parcel-type active-parcel" id="small">
                                                <i class="fa fa-file-alt fa-2x"></i>
                                                <h5 class="mb-1">Small</h5>
                                                <p class="fs-xxs">Important papers, small, reports</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="d-flex align-items-center cursor-pointer"
                                            onclick="document.querySelector('input[name=parcelType][value=medium]').click()">
                                            <div class="me-3">
                                                <span class="form-check-input" style="display: none;">
                                                    <input type="radio" class="form-check-input" name="parcelType"
                                                        value="medium" onclick="toggleBackground('medium')">
                                                </span>
                                            </div>
                                            <div class="text-center parcel-type" id="medium">
                                                <i class="fa fa-file-alt fa-2x"></i>
                                                <h5 class="mb-1">Medium</h5>
                                                <p class="fs-xxs">Coffee tables, vases, glassware</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="d-flex align-items-center cursor-pointer"
                                            onclick="document.querySelector('input[name=parcelType][value=large]').click()">
                                            <div class="me-3">
                                                <span class="form-check-input" style="display: none;">
                                                    <input type="radio" class="form-check-input" name="parcelType"
                                                        value="large" onclick="toggleBackground('large')">
                                                </span>
                                            </div>
                                            <div class="text-center parcel-type" id="large">
                                                <i class="fa fa-file-alt fa-2x"></i>
                                                <h5 class="mb-1">Large</h5>
                                                <p class="fs-xxs">Home appliances, furniture and other</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                    <div class="tab">
                        {{-- Booking Date and Time --}}
                        <div class="row">
                            {{-- Booking Date  --}}
                            <div class="col-md-6">
                                <label for="bookingDate">Booking Date</label>
                                <div class="mb-3">
                                    <input type="date" class="form-control" id="bookingDate" name="booking_date"
                                        placeholder="Enter booking date">
                                </div>
                            </div>
                            {{-- Booking Time --}}
                            <div class="col-md-6">
                                <label for="bookingTime">Booking Time</label>
                                <div class="mb-3">
                                    <input type="time" class="form-control" id="bookingTime" name="booking_time"
                                        placeholder="Enter booking time">
                                </div>
                            </div>
                        </div>
                        {{-- Booking Package Dimensions --}}
                        <div class="row">
                            {{-- Package Height  --}}
                            <div class="col-md-6">
                                <label for="packageHeight">Package Height</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Height"
                                        name="package_height" aria-describedby="package_height">
                                    <span class="input-group-text" id="package_height">INCH</span>
                                </div>
                            </div>
                            {{-- Package Width --}}
                            <div class="col-md-6">
                                <label for="packageWidth">Package Width</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Width" name="package_width"
                                        aria-describedby="package_width">
                                    <span class="input-group-text" id="package_width">INCH</span>
                                </div>
                            </div>
                        </div>
                        {{-- Other Details --}}
                        <div class="row">
                            {{-- Package Weight  --}}
                            <div class="col-md-6">
                                <label for="packageWeight">Package Weight</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Weight"
                                        name="package_weight" aria-describedby="package_weight">
                                    <span class="input-group-text" id="package_weight">KG</span>
                                </div>
                            </div>
                            {{-- Package Value --}}
                            <div class="col-md-6">
                                <label for="packageValue">Package Value</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Value" name="package_value"
                                        aria-describedby="package_value">
                                    <span class="input-group-text" id="package_value">$</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab">
                        <div class="row">
                            {{-- Add Sender Details --}}
                            <div class="col-md-6">
                                {{-- Receiver Name --}}
                                <div class="mb-3">
                                    <label for="receiverName">Receiver Name</label>
                                    <input type="text" class="form-control" id="receiverName" name="receiver_name"
                                        placeholder="Enter receiver name">
                                </div>
                                {{-- Receiver Email --}}
                                <div class="mb-3">
                                    <label for="receiverEmail">Receiver Email</label>
                                    <input type="email" class="form-control" id="receiverEmail" name="receiver_email"
                                        placeholder="Enter receiver email">
                                </div>
                                {{-- Receiver Phone --}}
                                <div class="mb-3">
                                    <label for="receiverPhone">Receiver Phone</label>
                                    <input type="text" class="form-control" id="receiverPhone" name="receiver_phone"
                                        placeholder="Enter receiver phone">
                                </div>
                                {{-- Delivery Note --}}
                                <div class="mb-3">
                                    <label for="deliveryNote">Delivery Note</label>
                                    <textarea class="form-control" id="deliveryNote" name="delivery_note" rows="3"></textarea>
                                </div>
                            </div>
                            {{-- Calculated Amount --}}
                            <div class="col-md-6">
                                <div class="card">
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
                            </div>
                        </div>
                    </div>

                    <div class="mt-5" style="overflow:auto;" id="nextprevious">
                        <div style="float:right;">
                            <button type="button" class="btn btn-primary" id="prevBtn"
                                onclick="nextPrev(-1)">Previous</button>
                            <button type="button" class="btn btn-primary" id="nextBtn"
                                onclick="nextPrev(1)">Next</button>
                        </div>
                    </div>
                </form>
                {{-- Success Message when form is submitted --}}
                <div class="row my-3" id="success-message" style="display: none;">
                    <div class="col-md-12 text-center">
                        <div class="alert alert-success alert-trim">
                            <i class="fa fa-check fa-3x mb-3"></i>
                            <h5>Your serivce has been booked successfully</h5>
                            <a href="{{ route('client.index') }}">Go to Dashboard</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- Custom JS for Form Page --}}
    <script>
        //your javascript goes here
        var currentTab = 0;
        document.addEventListener("DOMContentLoaded", function(event) {

            showTab(currentTab);

        });

        function showTab(n) {
            var x = document.getElementsByClassName("tab");
            x[n].style.display = "block";
            if (n == 0) {
                document.getElementById("prevBtn").style.display = "none";
            } else {
                document.getElementById("prevBtn").style.display = "inline";
            }
            if (n == (x.length - 1)) {
                document.getElementById("nextBtn").type = "button";
                document.getElementById("nextBtn").innerHTML = "Confirm Booking";
            } else {
                document.getElementById("nextBtn").type = "button";
                document.getElementById("nextBtn").innerHTML = "Next";
            }
            fixStepIndicator(n)
        }

        function nextPrev(n) {
            var x = document.getElementsByClassName("tab");
            // if (n == 1 && !validateForm()) return false;
            x[currentTab].style.display = "none";
            currentTab = currentTab + n;
            if (currentTab >= x.length) {
                // document.getElementById("newBookingForm").submit();
                // return false;
                alert("Service Booked Successfully");
                document.getElementById("nextprevious").style.display = "none";
                document.getElementById("all-steps").style.display = "none";
                document.getElementById("newBookingForm").style.display = "none";
                document.getElementById("success-message").style.display = "block";

            }
            showTab(currentTab);
        }


        function fixStepIndicator(n) {
            var i, x = document.getElementsByClassName("step");
            for (i = 0; i < x.length; i++) {
                x[i].className = x[i].className.replace(" active", "");
            }
            x[n].className += " active";
        }

        // Parcel Type changes background color function
        function toggleBackground(id) {
            var divs = document.querySelectorAll('.text-center');
            divs.forEach(function(div) {
                if (div.id === id) {
                    div.classList.add('active-parcel');
                } else {
                    div.classList.remove('active-parcel');
                }
            });
        }
    </script>

@endsection
