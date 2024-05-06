@extends('frontend.layouts.app')

@section('title', 'Join as Helper')

@section('content')

    {{-- Header Section  --}}
    <section class="py-5 bg-light-gray">
        <div class="container my-5">
            <div class="row align-items-center">
                <div class="col-md-6 px-5">
                    <h3>Join Us as a helper and earn money with us</h3>
                    <p>Be active, meet new people & make up to $2.5k/week!</p>
                    <a href="{{ route('helper.register') }}" class="btn btn-primary mt-3">Join as Helper</a>
                </div>
                <div class="col-md-6 text-center">
                    <img src="{{ asset('frontend/images/join-helper.png') }}" alt="Image" class="img-fluid">
                </div>
            </div>
        </div>
    </section>


    {{-- Join Us without Vehicle  --}}

    <section id="aboutus" class="container py-5">
        <div class="row align-items-center">
            <div class="col-lg-6 content">
                <div class="heading">
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
                    <a href="{{ route('helper.register') }}" class="btn btn-primary mt-3">Join as Helper</a>
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
    </section>


    {{-- What does it meant to become a helper?  --}}

    <section id="" class="py-5 bg-light-gray">
        <div class="container mt-5">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="heading">
                        <h2>What does it meant to become a helper?</h2>
                        <p>Anything can more or deliver with in 3 easy steps</p>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-4 mb-4 text-center">
                    <div class="p-5 mb-5 d-inline-flex bg-white rounded-circle">
                        <i class="fa fa-dollar fa-3x"></i>
                    </div>
                    <h5>Get big tips</h5>
                    <p>Our drivers and helpers make more money in tips than any other on-demand service. You keep 100% of
                        the tips you make.</p>
                </div>

                <div class="col-md-4 mb-4 text-center">
                    <div class="p-5 mb-5 d-inline-flex bg-white rounded-circle">
                        <i class="fa fa-calendar fa-3x"></i>
                    </div>
                    <h5>Work when you want</h5>
                    <p>Work on the weekends or every day of the week. Set your own schedule and work when you want.</p>
                </div>

                <div class="col-md-4 mb-4 text-center">
                    <div class="p-5 mb-5 d-inline-flex bg-white rounded-circle">
                        <i class="fa fa-dollar fa-3x"></i>
                    </div>
                    <h5>Get paid everyday</h5>
                    <p>Get paid at the end of each day via a direct deposit to your bank account.</p>
                </div>

            </div>
        </div>
    </section>

    {{-- Who qualifies? --}}
    <section>
        <div class="container mt-5">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="heading">
                        <h2>Who qualifies?</h2>
                        <p>Anything can more or deliver with in 3 easy steps</p>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-6 p-4 mb-4 bg-light d-flex align-items-center justify-content-between">
                    <div class="px-2">
                        <h6 class="mb-1">Recent smartphone</h6>
                        <p class="mb-0">Being very familiar with your iPhone or Android is key to this role.</p>
                    </div>
                    <div class="bg-primary text-white rounded-circle p-3">
                        <i class="fa fa-phone fa-2x"></i>
                    </div>
                </div>

                <div class="col-md-6 p-4 mb-4 bg-light d-flex align-items-center justify-content-between">
                    <div class="px-2">
                        <h6 class="mb-1">At least 18 years old</h6>
                        <p class="mb-0">You must be at least 18 years or older to be a Lugger.</p>
                    </div>
                    <div class="bg-primary text-white rounded-circle p-3">
                        <i class="fa fa-user fa-2x"></i>
                    </div>
                </div>

                <div class="col-md-6 p-4 mb-4 bg-light d-flex align-items-center justify-content-between">
                    <div class="px-2">
                        <h6 class="mb-1">Strong & physically able </h6>
                        <p class="mb-0">You must be strong and physically able to lift over 100 lbs.</p>
                    </div>
                    <div class="bg-primary text-white rounded-circle p-3">
                        <i class="fa-solid fa-dumbbell fa-2x"></i>
                    </div>
                </div>

                <div class="col-md-6 p-4 mb-4 bg-light d-flex align-items-center justify-content-between">
                    <div class="px-2">
                        <h6 class="mb-1">Great communication</h6>
                        <p class="mb-0">You must be good with people and have strong communication skills.</p>
                    </div>
                    <div class="bg-primary text-white rounded-circle p-3">
                        <i class="fa fa-users fa-2x"></i>
                    </div>
                </div>
            </div>
    </section>

    {{-- FAQs Section  --}}

    <section class="py-5 bg-light-gray">
        <div class="container mt-5">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="heading">
                        <h2>Frequently Asked Questions</h2>
                        <p>Here are some of our FAQs</p>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-lg-12">
                    <div class="accordion" id="accordionFAQs">
                        {{-- If $faqs are not empty and loop thorugh --}}
                        @forelse($faqs as $faq)
                            <div class="accordion-item mb-3">
                                <h2 class="accordion-header" id="faq_{{ $faq->id }}">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse{{ $faq->id }}" aria-expanded="true"
                                        aria-controls="collapse{{ $faq->id }}">
                                        {{ $faq->question }}
                                    </button>
                                </h2>
                                <div id="collapse{{ $faq->id }}" class="accordion-collapse collapse"
                                    aria-labelledby="faq_{{ $faq->id }}" data-bs-parent="#accordionFAQs">
                                    <div class="accordion-body">
                                        {{ $faq->answer }}
                                    </div>
                                </div>
                            </div>
                        @empty
                            {{-- <p>No FAQs Found</p> --}}
                            <div class="accordion-item mb-3">
                                <h2 class="accordion-header" id="heading3">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse3" aria-expanded="true" aria-controls="collapse3">
                                        How many items are include in the price of delivery?
                                    </button>
                                </h2>
                                <div id="collapse3" class="accordion-collapse collapse" aria-labelledby="heading3"
                                    data-bs-parent="#accordionFAQs">
                                    <div class="accordion-body">
                                        2 items are included in the price of delivery with our 24/7 delivery service, with
                                        additional items costing £5 per item.
                                    </div>
                                </div>
                            </div>
                        @endforelse


                    </div>
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
