@extends('frontend.layouts.app')

@section('title', 'Join as Helper')

@section('content')

    {{-- Header Section  --}}
    <section class=" bg-light-gray">
        <div class="container ">
            <div class="row align-items-center">
                <div class="col-md-6 px-5">
                    <h3>Join Us as a helper and earn money with us</h3>
                    <p>Be active, meet new people & make up to $2.5k/week!</p>
                    {{-- <a href="{{ route('helper.register') }}" class="btn btn-primary mt-3">Join as Helper</a> --}}
                    {{-- Redirect to Helper Register --}}
                    <div class="arrow-button">
                        <a href="{{ route('helper.register') }}">
                            <i class="fas fa-long-arrow-alt-right mr-2"></i> {{ __('frontend.join_as_helper') }}
                        </a>
                    </div>
                </div>
                <div class="col-md-6 text-center">
                    <img src="{{ asset('frontend/images/join-helper.png') }}" alt="Image" class="img-fluid">
                </div>
            </div>
        </div>
    </section>


    {{-- Join Us without Vehicle  --}}

    <section id="aboutus">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 content">
                    <div class="heading">
                        <h6>Vehicle Issue</h6>
                        <h2>Don't have your own vehicle?</h2>
                        <p>
                            Don't worry you can still apply as a helper! Owning a vehicle is not a requirement to becoming a
                            helper. Just select "No" for the question "Do you own a truck and want to use it withUs?".
                        </p>
                        <p>
                            Whether you're moving down the street or across the country, our dedicated team is committed to
                            providing top-notch service every step of the way. From carefully packing and loading your
                            belongings to navigating the logistics of transportation and delivery, we've got you covered.
                        </p>
                        {{-- Redirect to Helper Register --}}
                        <div class="arrow-button">
                            <a href="{{ route('helper.register') }}">
                                <i class="fas fa-long-arrow-alt-right mr-2"></i> {{ __('frontend.join_as_helper') }}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 slider">
                    <!-- Image Slider -->
                    <div id="carouselAboutIndicators" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="{{ asset('frontend/images/join-helper/image-1.png') }}" class="d-block mx-auto"
                                    height="400" alt="Image 1">
                            </div>
                            <div class="carousel-item">
                                <img src="{{ asset('frontend/images/join-helper/image-1.png') }}" class="d-block mx-auto"
                                    height="400" alt="Image 2">
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
        </div>
    </section>


    {{-- What does it meant to become a helper?  --}}

    <section class="bg-light-gray">
        <div class="container">
            <div class="row mb-5">
                <div class="col-lg-12 text-center">
                    <div class="heading">
                        <h6>Join as Helper</h6>
                        <h2>What does it meant to become a helper?</h2>
                        <p>Anything can more or deliver with in 3 easy steps</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="card mb-4 text-center">
                        <div class="card-body">
                            <div class="p-5 d-inline-flex ">
                                <i class="fa fa-dollar fa-3x text-primary"></i>
                            </div>
                            <h5>Get big tips</h5>
                            <p>Our drivers and helpers make more money in tips than any other on-demand service. You keep
                                100% of
                                the tips you make.</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card mb-4 text-center">
                        <div class="card-body">
                            <div class="p-5 d-inline-flex bg-white rounded-circle">
                                <i class="fa fa-calendar fa-3x text-primary"></i>
                            </div>
                            <h5>Work when you want</h5>
                            <p>Work on the weekends or every day of the week. Set your own schedule and work when you
                                want.</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card mb-4 text-center">
                        <div class="card-body">
                            <div class="p-5 d-inline-flex bg-white rounded-circle">
                                <i class="fa fa-dollar fa-3x text-primary"></i>
                            </div>
                            <h5>Get paid everyday</h5>
                            <p>Get paid at the end of each day via a direct deposit to your bank account.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- Who qualifies? --}}
    <section>
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="heading">
                        <h6>Want to become a helper?</h6>
                        <h2>Who qualifies?</h2>
                        <p>Anything can more or deliver with in 3 easy steps</p>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div class="px-2">
                                <h6 class="mb-1">Recent smartphone</h6>
                                <p class="mb-0">Being very familiar with your iPhone or Android is key to this role.</p>
                            </div>
                            <div class="bg-primary text-white rounded-1 p-3">
                                <i class="fa fa-phone fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div class="px-2">
                                <h6 class="mb-1">At least 18 years old</h6>
                                <p class="mb-0">You must be at least 18 years or older to be a Lugger.</p>
                            </div>
                            <div class="bg-primary text-white rounded-1 p-3">
                                <i class="fa fa-user fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div class="px-2">
                                <h6 class="mb-1">Strong & physically able</h6>
                                <p class="mb-0">You must be strong and physically able to lift over 100 lbs.</p>
                            </div>
                            <div class="bg-primary text-white rounded-1 p-3">
                                <i class="fa fa-dumbbell fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div class="px-2">
                                <h6 class="mb-1">Great communication</h6>
                                <p class="mb-0">You must be good with people and have strong communication skills.</p>
                            </div>
                            <div class="bg-primary rounded-1 text-white p-3">
                                <i class="fa fa-users fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
    </section>

    {{-- FAQs Section  --}}
    @include('frontend.includes.faqs')

    {{-- Get Apps Section  --}}
    @include('frontend.includes.getapps')

@endsection
