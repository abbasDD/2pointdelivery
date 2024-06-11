@extends('frontend.layouts.app')

@section('title', 'Services')

@section('content')

    {{-- Header Section  --}}
    <section class="py-3 bg-light-gray">
        <div class="container my-5">
            <div class="row align-items-center">
                <div class="col-md-7 px-5">
                    <h2>Discover the Comprehensive <span class="text-primary">Range of Services</span> We Offer</h2>
                    <p>Embark on a journey with us as we unveil a plethora of services tailored to meet your every need. At
                        2 Point Delivery, we take pride in delivering excellence at every touchpoint, ensuring that each
                        interaction leaves you not just satisfied, but delighted. </p>
                    {{-- <a href="#" class="btn btn-primary mt-3">Book a Service</a> --}}
                    {{-- Redirect to Book Service --}}
                    <div class="read-more">
                        <a href="{{ route('newBooking') }}">
                            <i class="fas fa-long-arrow-alt-right mr-2"></i> Book a Service
                        </a>
                    </div>

                </div>
                <div class="col-md-5 text-center">
                    <img src="{{ asset('frontend/images/services/service-bg.png') }}" alt="Image" class="img-fluid">
                </div>
            </div>
        </div>
    </section>


    {{-- Services Section  --}}
    <section class="py-5">
        <div class="container mt-5">
            <div class="row justify-content-center mb-5">
                <div class="col-lg-8 text-center">
                    <div class="heading">
                        <h6>What We Offer</h6>
                        <h2>Our Valued Services</h2>
                        <p>In the realm of logistics delivery, efficiency and reliability are paramount. Our logistics
                            delivery services are meticulously designed to streamline the transportation of goods from point
                            A to point B seamlessly. With a focus on precision timing and route optimization, we ensure that
                            your packages arrive at their destination promptly and intact. </p>
                    </div>
                </div>
            </div>

            {{-- Small Service 01 --}}
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <img src="{{ asset('frontend/images/services/service-1.png') }}" alt="Image"
                                class="img-fluid mx-auto">
                        </div>
                        <div class="col-md-6">
                            <div class="heading p-3">
                                <h2 class="mb-2">Small</h2>
                                <p class="mb-1">Introducing our Small Item Delivery Service - because we understand the
                                    importance of life's little essentials. Whether it's your phone, keys, or glasses that
                                    you've left behind, we're here to swiftly deliver them to your doorstep. With our
                                    reliable and efficient service, you can rest assured that your essentials will be safely
                                    returned to you, allowing you to carry on with your day without missing a beat. Simply
                                    reach out to us, and consider it delivered!</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="bg-light-gray py-5 text-center">
                                <div class="mb-1">
                                    <h3 class="mb-1 text-primary">$9.99 </h3>
                                    <p class="text-muted fs-xs">per km</p>
                                    <a href="#" class="btn btn-primary mt-3">Book Now</a>
                                </div>
                                <a href="#" class="text-muted fs-xxs">Terms & Conditions Apply</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Medium Service 02 --}}
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <img src="{{ asset('frontend/images/services/service-2.png') }}" alt="Image"
                                class="img-fluid mx-auto">
                        </div>
                        <div class="col-md-6">
                            <div class="heading p-3">
                                <h2 class="mb-2">Medium</h2>
                                <p class="mb-1">Introducing our Medium Item Delivery Service, catering to your everyday
                                    needs beyond the basics. From groceries to laptops and clothes, we've got you covered.
                                    No need to worry about hauling bulky bags or navigating crowded stores – simply leave it
                                    to us. Our dedicated team ensures prompt and secure delivery of your medium-sized
                                    essentials right to your doorstep. Whether it's a weekly grocery run or a new outfit for
                                    that special occasion, count on us to deliver with efficiency and care. Sit back, relax,
                                    and let us handle the heavy lifting while you focus on what matters most</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="bg-light-gray py-5 text-center">
                                <div class="mb-1">
                                    <h3 class="mb-1 text-primary">$24.99 </h3>
                                    <p class="text-muted fs-xs">per km</p>
                                    <a href="#" class="btn btn-primary mt-3">Book Now</a>
                                </div>
                                <a href="#" class="text-muted fs-xxs">Terms & Conditions Apply</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Large Service 01 --}}
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <img src="{{ asset('frontend/images/services/service-3.png') }}" alt="Image"
                                class="img-fluid mx-auto">
                        </div>
                        <div class="col-md-6">
                            <div class="heading p-3">
                                <h2 class="mb-2">Large</h2>
                                <p class="mb-1">Introducing our Heavy Item Delivery Service, designed to handle your most
                                    substantial deliveries with ease. Whether it's industrial equipment, furniture, or large
                                    appliances weighing 50+ kgs, we've got the expertise and resources to ensure safe and
                                    efficient transportation. Our team of experienced professionals will carefully handle
                                    your items from pick-up to drop-off, providing peace of mind throughout the process.
                                    With our reliable service, you can trust that your large items will reach their
                                    destination securely and on time. Say goodbye to the hassle of logistics and hello to
                                    seamless delivery solutions.</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="bg-light-gray py-5 text-center">
                                <div class="mb-1">
                                    <h3 class="mb-1 text-primary">$39.99 </h3>
                                    <p class="text-muted fs-xs">per km</p>
                                    <a href="#" class="btn btn-primary mt-3">Book Now</a>
                                </div>
                                <a href="#" class="text-muted fs-xxs">Terms & Conditions Apply</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    {{-- Still looking for help ? --}}
    @include('frontend.includes.ctahelp')


    {{-- How It Works Section  --}}
    @include('frontend.includes.howitworks')


    {{-- Get Apps Section  --}}
    @include('frontend.includes.getapps')




@endsection
