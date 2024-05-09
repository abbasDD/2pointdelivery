@extends('frontend.layouts.app')

@section('title', 'New Booking')

@section('content')


    {{-- Define some javascript variables to be used in JS --}}
    {{-- Load js/variables.blade.php --}}
    @include('frontend.bookings.js.variables')

    {{-- HTML Code here --}}
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
                                <script
                                    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD-jXtk8qCpcwUwFn-7Q3VazeneJJ46g00&libraries=places&callback=initMap"
                                    async defer></script>
                                {{-- Load mapjs script here --}}
                                @include('frontend.bookings.js.map')
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
                                <div class="col-md-4">
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
                                <div class="col-md-4">
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
                                <div class="col-md-4">
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
                                @if (config('declare_package_value') == 'yes')
                                    {{-- Package Value --}}
                                    <div class="col-md-6">
                                        <label for="packageValue">Package Value</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" placeholder="Value"
                                                name="package_value" aria-describedby="package_value"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
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
                                {{-- Floor Assess --}}
                                <div class="col-md-6">
                                    <label for="floorAssess">Floor Assess</label>
                                    <div class="input-group mb-3">
                                        <select class="form-control moving-field" name="floor_assess"
                                            aria-label="Floor Assess" onchange="updatePaymentAmount()">
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
                                            name="receiver_name" placeholder="Enter receiver name" required>
                                    </div>
                                </div>
                                {{-- Receiver Email --}}
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="receiverEmail">Receiver Email</label>
                                        <input type="email" class="form-control" id="receiverEmail"
                                            name="receiver_email" placeholder="Enter receiver email" required>
                                    </div>
                                </div>
                                {{-- Receiver Phone --}}
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="receiverPhone">Receiver Phone</label>
                                        <input type="text" class="form-control" id="receiverPhone"
                                            name="receiver_phone" placeholder="Enter receiver phone" required>
                                    </div>
                                </div>
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
    @include('frontend.bookings.js.custom')


@endsection
