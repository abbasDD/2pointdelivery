@extends('frontend.layouts.app')

@section('title', 'Home')

@section('content')

    {{-- Header Section  --}}
    <section id="pageheader" style="background-image: url({{ asset('frontend/images/header/header-bg.png') }})">
        <div class="container h-100">
            <div class="d-flex align-items-center h-100">
                <div class="content">
                    <h3 class="mb-2">{{ __('frontend.header_title') }}</h3>
                    <p class="text-white">{{ __('frontend.header_subtitle') }}</p>
                    {{-- <a href="{{ route('helper.register') }}" class="btn btn-primary">{{ __('frontend.join_as_helper') }}</a> --}}
                    {{-- Redirect to Helper Register --}}
                    <div class="arrow-button">
                        <a href="{{ route('helper.register') }}" class="text-white">
                            <i class="fas fa-long-arrow-alt-right mr-2"></i> {{ __('frontend.join_as_helper') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Move & Delivery Form Section  --}}
    <section class="container mt-5">
        <div class="text-center heading">
            <h6>{{ __('frontend.move_and_deliver.title') }}</h6>
            <h2>{{ __('frontend.move_and_deliver.subtitle') }}</h2>
        </div>
        <form action="{{ route('newBooking') }}" method="GET">
            <div class="row">
                {{-- Select Service --}}
                <div class="col-md-2">
                    <div class="mb-3">
                        <select class="form-control" name="serviceType">
                            <option value="" disabled>Select Service</option>
                            @if (!isset($serviceTypes))
                                <option value="delivery">Delivery</option>
                                {{-- <option value="moving" selected>Moving</option> --}}
                            @else
                                @foreach ($serviceTypes as $serviceType)
                                    <option value="{{ $serviceType->id }}"
                                        {{ isset($serviceType) && $serviceType->id == old('serviceType', $serviceType->id) ? 'selected' : '' }}>
                                        {{ $serviceType->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <input id="pickupLocation" class="form-control" type="text" name="pickup_address"
                            placeholder="Enter pickup location" required>
                        <input type="hidden" id="pickup_latitude" name="pickup_latitude" />
                        <input type="hidden" id="pickup_longitude" name="pickup_longitude" />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <input id="deliveryLocation" class="form-control" type="text" name="dropoff_address"
                            placeholder="Enter delivery location" required>
                        <input type="hidden" id="dropoff_latitude" name="dropoff_latitude" />
                        <input type="hidden" id="dropoff_longitude" name="dropoff_longitude" />
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn arrow-button w-100"><i class="fas fa-long-arrow-alt-right"></i> Get
                        Estimate </button>
                </div>
            </div>
        </form>
        <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places"></script>
        <script>
            var pickupAutocomplete = new google.maps.places.Autocomplete(document.getElementById('pickupLocation'));
            var deliveryAutocomplete = new google.maps.places.Autocomplete(document.getElementById('deliveryLocation'));

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
    </section>

    {{-- About Us Section  --}}
    @include('frontend.includes.about')


    {{-- How It Works Section  --}}
    @include('frontend.includes.howitworks')

    {{-- Testimonials --}}
    @include('frontend.includes.testimonials')

    {{-- Global Reach --}}
    @include('frontend.includes.globalreach')

    {{-- Get Apps Section  --}}
    @include('frontend.includes.getapps')




@endsection
