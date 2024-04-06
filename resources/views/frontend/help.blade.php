@extends('frontend.layouts.app')

@section('title', 'Help')

@section('content')

    {{-- Header Section  --}}
    <section class="py-5 bg-light-gray">
        <div class="container my-5">
            <div class="row align-items-center">
                <div class="col-md-6 px-5">
                    <h3 class="mb-1">How can we help you?</h3>
                    <p class="mb-3">At 2 Point, we operate around the clock to serve you whenever you need us. Day or
                        night, our delivery services are at your disposal, ensuring convenience at every hour.</p>
                    <form>
                        <div class="form-group d-flex">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text h-100"><i class="fa fa-search"></i></span>
                                </div>
                                <input type="text" class="form-control" id="search" placeholder="Search your topic">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-6 text-center">
                    <img src="{{ asset('frontend/images/help-bg.png') }}" alt="Help Image" width="300" class="img-fluid">
                </div>
            </div>
        </div>
    </section>


    {{-- All Topics --}}

    <section class="container my-5">
        <div class="row">
            <div class="col-lg-12">
                <div class="heading text-center">
                    <h2>All topics</h2>
                    <p>
                        Don't worry you can still apply as a helper!
                    </p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 p-2">
                <div class="bg-light-gray p-4">
                    <h5 class="mb-0">How 2 point works</h5>
                    <p>What we do, areas we serve, communicating with us</p>
                </div>
            </div>
            <div class="col-md-6 p-2">
                <div class="bg-light-gray p-4">
                    <h5 class="mb-0">Delivery in progress</h5>
                    <p>Tracking your order, communicating with your helper.</p>
                </div>
            </div>
            <div class="col-md-6 p-2">
                <div class="bg-light-gray p-4">
                    <h5 class="mb-0">Booking a service</h5>
                    <p>scheduling, requesting, making changes.</p>
                </div>
            </div>
            <div class="col-md-6 p-2">
                <div class="bg-light-gray p-4">
                    <h5 class="mb-0">Pricing discount & fees</h5>
                    <p>Pricing, promotions, referrals, taxes & fees</p>
                </div>
            </div>
        </div>
    </section>


    {{-- Still looking for help ? --}}

    <section>

        <div class="container my-5">
            <div class="row align-items-center bg-primary-light text-white p-5 rounded-10">
                <div class="col-md-8">
                    <div class="heading">
                        <h2 class="mb-1 text-white">Still looking for help?</h2>
                        <p>Count on us for assistance whenever you need it. Our 24/7 operation ensures we're here for you
                            anytime, day or night.</p>
                        <a class="btn btn-white" href="#">Contact Us</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <img src="{{ asset('frontend/images/help-cta.png') }}" alt="Image" class="img-fluid mx-auto">
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
                    <div class="accordion" id="accordionExample">
                        <div class="accordion-item mb-3">
                            <h2 class="accordion-header" id="heading1">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse1" aria-expanded="true" aria-controls="collapse1">
                                    What hours does 2 point operate?
                                </button>
                            </h2>
                            <div id="collapse1" class="accordion-collapse collapse" aria-labelledby="heading1"
                                data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    2 Point operates on a 24/7 basis, catering to your delivery needs at any hour of the day
                                    or night. Whether you're craving a midnight snack, need groceries in the early morning,
                                    or require urgent document delivery during off-peak hours, we've got you covered. Our
                                    commitment to round-the-clock service ensures that you can rely on us whenever you need
                                    fast, efficient, and convenient delivery solutions. With 2 Point, convenience knows no
                                    time constraints.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item mb-3">
                            <h2 class="accordion-header" id="heading2">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse2" aria-expanded="true" aria-controls="collapse2">
                                    Will you bring my items inside?
                                </button>
                            </h2>
                            <div id="collapse2" class="accordion-collapse collapse" aria-labelledby="heading2"
                                data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    Our delivery service includes up to 2 items in the delivery price, with additional items
                                    costing £5 per item.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item mb-3">
                            <h2 class="accordion-header" id="heading3">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse3" aria-expanded="true" aria-controls="collapse3">
                                    How many items are include in the price of delivery?
                                </button>
                            </h2>
                            <div id="collapse3" class="accordion-collapse collapse" aria-labelledby="heading3"
                                data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    2 items are included in the price of delivery with our 24/7 delivery service, with
                                    additional items costing £5 per item.
                                </div>
                            </div>
                        </div>
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
