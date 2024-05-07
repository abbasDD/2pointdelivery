@extends('frontend.layouts.app')

@section('title', 'Home')

@section('content')

    {{-- Header Section  --}}
    <section id="pageheader" style="background-image: url({{ asset('frontend/images/header/header-bg.png') }})">
        <div class="container h-100">
            <div class="d-flex align-items-center h-100">
                <div class="content">
                    <h3 class="mb-2">Booking & Moving Simplified: Your Journey, Our Priority!</h3>
                    <p>From the first click to the final destination, we make booking and moving effortless. Trust us to
                        handle your logistics with precision and care, ensuring a smooth and stress-free experience every
                        step of the way!</p>
                    <a href="{{ route('helper.register') }}" class="btn btn-primary">{{ __('frontend.join_as_helper') }}</a>
                </div>
            </div>
        </div>
    </section>

    {{-- Move & Delivery Form Section  --}}
    <section class="container mt-5">
        <div class="text-center heading">
            <h2>Move or Deliver Anything</h2>
            <p>Efficient logistics solutions to transport or deliver any item, anywhere, anytime.</p>
        </div>
        <form action="{{ route('new_booking') }}" method="GET">
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
                    <button type="submit" class="btn btn-primary w-100">Get Estimate</button>
                </div>
            </div>
        </form>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD-jXtk8qCpcwUwFn-7Q3VazeneJJ46g00&libraries=places">
        </script>
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

    <section id="aboutus" class="container mt-5">
        <div class="row align-items-center">
            <div class="col-lg-6 content">
                <div class="heading">
                    <h2>Delightful delivery & moving</h2>
                    <p>
                        Let us shoulder the weight of delivering your cherished possessions with ease. Entrust us with the
                        responsibility of ensuring your items reach their destination stress-free. Our mission? To transform
                        your relocation journey into a seamless adventure by handling every aspect of the move, leaving you
                        free to focus on what matters most: embracing your new beginnings.
                    </p>
                    <p>
                        Whether you're moving down the street or across the country, our dedicated team is committed to
                        providing top-notch service every step of the way. From carefully packing and loading your
                        belongings to navigating the logistics of transportation and delivery, we've got you covered.
                    </p>
                </div>
            </div>
            <div class="col-lg-6 slider">
                <!-- Image Slider -->
                <div id="carouselAboutIndicators" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="{{ asset('frontend/images/about/image-1.png') }}" class="d-block mx-auto"
                                alt="Image 1">
                        </div>
                        <div class="carousel-item">
                            <img src="{{ asset('frontend/images/about/image-2.png') }}" class="d-block mx-auto"
                                alt="Image 2">
                        </div>
                        <!-- Add more carousel items here -->
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselAboutIndicators"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselAboutIndicators"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </div>
    </section>


    {{-- How It Works Section  --}}

    <section id="howitworks" class="howitworks bg-light-gray">
        <div class="container mt-5">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="heading">
                        <h2>How it works</h2>
                        <p>Anything can more or deliver with in 3 easy steps</p>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-4 mb-4 text-center">
                    <img src="{{ asset('frontend/images/howitworks/image-1.png') }}" alt="Image 1" class="img-fluid mb-3">
                    <h3>Select our service</h3>
                    <p>First select the service which you want to avail, either you want our delivery or moving service.</p>
                </div>
                <div class="col-md-4 mb-4 text-center">
                    <img src="{{ asset('frontend/images/howitworks/image-2.png') }}" alt="Image 2" class="img-fluid mb-3">
                    <h3>Book Service</h3>
                    <p>Set your pickup & drop-off location, select time and select the vehicle that is right for you, </p>
                </div>
                <div class="col-md-4 mb-4 text-center">
                    <img src="{{ asset('frontend/images/howitworks/image-3.png') }}" alt="Image 3" class="img-fluid mb-3">
                    <h3>Ley Us Take Care</h3>
                    <p>Let us take the responsibility of your items to get delivered & l move for you.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Get Apps Section  --}}
    <section id="getapps" class="container mt-5">
        <div class="row align-items-center">
            <!-- Left Side (Image and Heading) -->
            <div class="col-lg-8">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <img src="{{ asset('frontend/images/mobile.png') }}" alt="Image" class="img-fluid">
                    </div>
                    <div class="col-sm-6">
                        <h2>Try 2 point client app</h2>
                        <p>make your items get delivered or pack&move</p>
                    </div>
                </div>


            </div>

            <!-- Right Side (App Store and Play Store Links) -->
            <div class="col-lg-4 mt-4 mt-lg-0">
                <div class="row mx-3">
                    <div class="col-md-12">
                        <h2>Get your app today</h2>
                    </div>
                    <div class="col-sm-6">
                        <a href="#" target="_blank">
                            <img src="{{ asset('frontend/images/play-store.png') }}" alt="Play Store" class="img-fluid">
                        </a>
                    </div>
                    <div class="col-sm-6">
                        <a href="#" target="_blank">
                            <img src="{{ asset('frontend/images/app-store.png') }}" alt="App Store" class="img-fluid">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>




@endsection
