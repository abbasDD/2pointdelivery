@extends('frontend.layouts.app')

@section('title', 'New Booking')

@section('content')

    {{-- HTML Code here --}}
    <section class="section">
        <div class="container py-5">
            <div class="row d-flex justify-content-center align-items-center">
                <div class="col-md-12">
                    <form class="booking-form" id="newBookingForm">
                        <div class="d-flex align-items-center justify-content-between mb-5">
                            <div class="heading">
                                <h2 class="mb-1">Book with Us</h2>
                                <p>Please fill out the form below to get a quote for your shipment.</p>
                            </div>
                            @if (count($addresses) > 0)
                                <div class="options">
                                    <select class="form-control" onchange="setAddressBook(this.value)">
                                        <option value="0" selected disabled>Load from Addresses</option>
                                        @foreach ($addresses as $address)
                                            <option value="{{ $address->id }}">{{ $address->receiver_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                        </div>
                        {{-- Booking Form --}}
                        <div class="row">
                            @csrf
                            <div class="col-md-12">
                                {{-- Showing text fields to add pickup and drop off --}}
                                <div class="row">
                                    {{-- Pickup Location --}}
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="pickup_address">Pickup Location</label>
                                            <input id="pickup_address" class="form-control" type="text"
                                                name="pickup_address" placeholder="Enter pickup location"
                                                value="{{ request()->get('pickup_address') }}" required>
                                            <input type="hidden" id="pickup_latitude" name="pickup_latitude"
                                                value="{{ request()->get('pickup_latitude') }}" required>
                                            <input type="hidden" id="pickup_longitude" name="pickup_longitude"
                                                value="{{ request()->get('pickup_longitude') }}" required>
                                        </div>
                                    </div>
                                    {{-- Delivery Location --}}
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="dropoff_address">Delivery Location</label>
                                            <input id="dropoff_address" class="form-control" type="text"
                                                name="dropoff_address" placeholder="Enter delivery location"
                                                value="{{ request()->get('dropoff_address') }}" required>
                                            <input type="hidden" id="dropoff_latitude" name="dropoff_latitude"
                                                value="{{ request()->get('dropoff_latitude') }}" required>
                                            <input type="hidden" id="dropoff_longitude" name="dropoff_longitude"
                                                value="{{ request()->get('dropoff_longitude') }}" required>
                                        </div>
                                    </div>

                                    {{-- Load JS of Map --}}
                                    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places"></script>
                                    <script>
                                        var options = {
                                            componentRestrictions: {
                                                country: ["ca", "us"]
                                            }
                                        };

                                        var pickupAutocomplete = new google.maps.places.Autocomplete(document.getElementById('pickup_address'), options);
                                        var deliveryAutocomplete = new google.maps.places.Autocomplete(document.getElementById('dropoff_address'),
                                            options);

                                        pickupAutocomplete.addListener('place_changed', function() {
                                            var place = pickupAutocomplete.getPlace();
                                            document.getElementById('pickup_latitude').value = place.geometry.location.lat();
                                            document.getElementById('pickup_longitude').value = place.geometry.location.lng();
                                        });
                                        deliveryAutocomplete.addListener('place_changed', function() {
                                            var place = deliveryAutocomplete.getPlace();
                                            document.getElementById('dropoff_latitude').value = place.geometry.location.lat();
                                            document.getElementById('dropoff_longitude').value = place.geometry.location.lng();
                                        });
                                    </script>

                                    {{-- Booking Date  --}}
                                    <div class="col-md-3">
                                        <label for="bookingDate">Booking Date</label>
                                        <div class="mb-3">
                                            <input type="date" class="form-control" id="bookingDate" name="booking_date"
                                                value="<?php echo date('Y-m-d'); ?>" required>
                                        </div>
                                    </div>
                                    {{-- Booking Time --}}
                                    <div class="col-md-3">
                                        <label for="bookingTime">Booking Time</label>
                                        <div class="mb-3">
                                            <input type="time" class="form-control" id="bookingTime" name="booking_time"
                                                value="<?php echo date('H:i'); ?>" required>
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
                                            <select class="form-control h-100" id="priorityDropdown" name="priority"
                                                aria-label="Priority" required>
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
                                                    <div class="col-md-3">
                                                        <div class="d-flex align-items-center cursor-pointer"
                                                            onclick="toggleBackground('{{ $serviceCategory->uuid }}')">
                                                            <div class="me-3">
                                                                <span class="form-check-input" style="display: none;">
                                                                    <input type="radio" class="form-check-input"
                                                                        name="parcelType"
                                                                        value="{{ $serviceCategory->id }}"
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
                                </div>

                                {{-- Delivery Package Details --}}
                                @include('frontend.bookings.partials.new.delivery')

                                {{-- Moving Package Details --}}
                                @include('frontend.bookings.partials.new.moving')

                                {{-- Receiver Details --}}
                                {{-- @include('frontend.bookings.partials.new.receiver') --}}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <button type="submit" class="btn btn-primary btn-block">Get Estimate</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    {{-- Define some javascript variables to be used in JS --}}
    @include('frontend.bookings.js.booking')

@endsection
