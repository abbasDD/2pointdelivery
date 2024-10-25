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
                        @csrf
                        <div class="card">
                            <div class="card-body">
                                {{-- Showing text fields to add pickup and drop off --}}
                                <div class="row">
                                    {{-- Pickup Location --}}
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="pickup_address">Pickup Location</label>
                                            <input id="pickup_address" class="form-control" type="text"
                                                name="pickup_address" placeholder="Enter pickup location"
                                                value="{{ request()->get('pickupLocation') }}" required>
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
                                                value="{{ request()->get('deliveryLocation') }}" required>
                                            <input type="hidden" id="dropoff_latitude" name="dropoff_latitude"
                                                value="{{ request()->get('dropoff_latitude') }}" required>
                                            <input type="hidden" id="dropoff_longitude" name="dropoff_longitude"
                                                value="{{ request()->get('dropoff_longitude') }}" required>
                                        </div>
                                    </div>

                                    {{-- Load JS of Map --}}
                                    <script
                                        src="https://maps.googleapis.com/maps/api/js?key={{ config('google_map_api_key') ?? 'Your API Key' }}&libraries=places">
                                    </script>
                                    <script>
                                        var options = {
                                            componentRestrictions: {
                                                country: ["ca"]
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

                                    {{-- Booking Date --}}
                                    <div class="col-md-3">
                                        <label for="bookingDate">Booking Date</label>
                                        <div class="mb-3">
                                            <input type="text" class="form-control" id="bookingDate" name="booking_date"
                                                value="<?php echo date(config('date_format') ?: 'd-m-Y'); ?>" required>
                                        </div>
                                    </div>
                                    <script>
                                        // Convert PHP date format to jQuery UI datepicker format
                                        function convertDateFormat(phpFormat) {
                                            const formatMapping = {
                                                'Y': 'yy', // 4-digit year
                                                'm': 'mm', // 2-digit month
                                                'd': 'dd', // 2-digit day
                                                'j': 'd', // Day of the month without leading zeros
                                                'n': 'm', // Month without leading zeros
                                                'M': 'M', // Short textual representation of a month
                                                'D': 'D' // Day of the week short textual representation
                                            };
                                            return phpFormat.replace(/Y|m|d|j|n|M|D/g, function(match) {
                                                return formatMapping[match];
                                            });
                                        }

                                        var dateFormat = convertDateFormat("<?php echo config('date_format') ?? 'd-m-Y'; ?>");

                                        // Initialize the date picker with the correct format
                                        $('#bookingDate').datepicker({
                                            dateFormat: dateFormat
                                        });
                                    </script>

                                    {{-- Booking Time --}}
                                    <div class="col-md-3">
                                        <label for="bookingTime">Booking Time</label>
                                        <div class="mb-3">
                                            <input type="text" class="form-control" id="bookingTime" name="booking_time"
                                                value="<?php echo date(config('time_format') ?? 'H:i'); ?>" required>
                                        </div>
                                    </div>
                                    <script>
                                        // Initialize the time picker with the correct format
                                        var timeFormat = "{{ config('time_format') ?? 'H:i' }}";
                                        $('#bookingTime').timepicker({
                                            timeFormat: timeFormat,
                                            interval: 30,
                                            minTime: '00:00',
                                            maxTime: '23:30',
                                            dynamic: false,
                                            dropdown: true,
                                            scrollbar: true
                                        });
                                    </script>

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
                                                    @if ($serviceType->id == request()->get('serviceTypeID'))
                                                        <script>
                                                            selectedServiceType = '{{ $serviceType->type }}';
                                                        </script>
                                                    @endif
                                                    {{-- Select Option --}}
                                                    <option value="{{ $serviceType->id }}"
                                                        {{ isset($serviceType) && $serviceType->id == request()->get('serviceTypeID') ? 'selected' : '' }}>
                                                        {{ $serviceType->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    {{-- Priority --}}
                                    <div class="col-md-6">
                                        <label for="priority">Priority</label>
                                        <div class="mb-3">
                                            <select class="form-control h-100" id="priorityDropdown"
                                                onchange="setPriority()" name="priority" aria-label="Priority" required>
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
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                {{-- Delivery Package Details --}}
                                @include('frontend.bookings.partials.new.delivery')

                                {{-- Moving Package Details --}}
                                @include('frontend.bookings.partials.new.moving')

                                {{-- Secureship Details --}}
                                @include('frontend.bookings.partials.new.secureship')
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">

                                {{-- Receipent Details --}}
                                @include('frontend.bookings.partials.new.receiver')

                            </div>
                        </div>

                        {{-- Include a Submit Button --}}
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <button type="submit" class="btn btn-primary btn-block">Get Estimate</button>
                            </div>
                        </div>

                        {{-- Get and Show Calculated Price --}}
                        @include('frontend.bookings.partials.new.cart')
                    </form>
                </div>
            </div>
        </div>
    </section>

    {{-- Define some javascript variables to be used in JS --}}
    @include('frontend.bookings.js.booking')

    {{-- secureship JS --}}
    @include('frontend.bookings.js.secureship')

    <script>
        $(document).ready(function() {
            parcelCategoriesDiv();
        });
    </script>

@endsection
