@extends('frontend.layouts.app')

@section('title', 'About Us')

@section('content')

    {{-- Header Section  --}}
    <section class="py-5 bg-light-gray">
        <div class="container my-5">
            <div class="row justify-content-center">
                <div class="col-md-8 px-5 heading">
                    <h2 class="text-center">About Us</h2>
                    <h5 class="text-center">"we specialize in providing reliable moving and delivery services to customers
                        around the world."
                    </h5>
                    <p class="mb-3 text-center">Our mission is to make the moving and delivery process stress-free for you.
                    </p>
                </div>
                <div class="col-md-12 text-center">
                    <img src="{{ asset('frontend/images/about/about-header.png') }}" alt="About Image" class="img-fluid">
                </div>
            </div>
        </div>
    </section>


    {{-- + 7800 Successful deliveries --}}

    <section class="py-5 bg-light-gray">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-9">
                    <div class="heading">
                        <h2 class="mb-1">+ 7800 Successful deliveries</h2>
                        <p class="mb-2">Our successful deliveries are a testament to our dedication and precision in every
                            move. With meticulous planning and a commitment to excellence, we ensure each item reaches its
                            destination safely and on time. From fragile antiques to bulky furniture, our team handles every
                            shipment with care and expertise. Trust us to deliver your belongings with confidence and
                            reliability.</p>
                        <p>Our global team of helpers brings expertise and dedication to every corner of the world, ensuring
                            seamless relocations and deliveries. With local knowledge and a commitment to excellence, they
                            provide reliable assistance wherever you need it.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="border-left p-5 text-center">
                        <div class="mb-2">
                            <h3>2009</h3>
                            <p>Established</p>
                        </div>
                        <div class="mb-2">
                            <h3>7500 +</h3>
                            <p>Current Helpers</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-md-4">
                    <img src="{{ asset('frontend/images/about/mission-img.png') }}" alt="mission Image" class="img-fluid">
                </div>
                <div class="col-md-8">
                    <div class="heading">
                        <h2 class="mb-3">Our Mission and Goals</h2>
                        <p class="mb-2">At our core, we aim to redefine the moving and delivery experience, driven by a
                            mission to provide seamless, stress-free services. Our approach combines cutting-edge technology
                            with personalized care, ensuring every client receives the attention they deserve. </p>
                        <p class="mb-2">With a focus on efficiency and reliability, we meticulously plan and execute each
                            task, striving
                            to exceed expectations at every turn. Our team of skilled professionals works collaboratively,
                            leveraging their expertise to tackle challenges and deliver exceptional results. </p>
                        <p class="mb-2">Trust us to handle your relocation or delivery with precision, integrity, and a
                            dedication to excellence.</p>
                    </div>
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

    {{-- Team Section  --}}

    <section class="py-5 bg-light-gray">
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <div class="heading">
                        <h2>Our Team</h2>
                        <p>Our team is a dynamic blend of talent and dedication. With a collaborative spirit and unwavering
                            commitment, we tackle every challenge with enthusiasm and expertise. From customer service to
                            logistics and operations.</p>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-3">
                    <div class="team-member text-center mb-3">
                        <img src="{{ asset('frontend/images/team/team-1.png') }}" alt="Team Member 1"
                            class="img-fluid rounded-circle mb-3">
                        <h5 class="mb-2">Shankar Jai</h5>
                        <p class="mb-1">Founder</p>
                        <a href="https://twitter.com/jai_shan" target="_blank"
                            class="btn btn-primary btn-sm rounded-pill mt-3"
                            style="background-color: #1DA1F2; border-color: #1DA1F2;">
                            <i class="fab fa-twitter fa-fw" aria-hidden="true"></i>
                            <span class="d-none d-md-inline">Follow @jai_shan</span>
                        </a>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="team-member text-center mb-3">
                        <img src="{{ asset('frontend/images/team/team-2.png') }}" alt="Team Member 1"
                            class="img-fluid rounded-circle mb-3">
                        <h5 class="mb-2">Jhon Carlos</h5>
                        <p class="mb-1">Founder</p>
                        <a href="https://twitter.com/jai_shan" target="_blank"
                            class="btn btn-primary btn-sm rounded-pill mt-3"
                            style="background-color: #1DA1F2; border-color: #1DA1F2;">
                            <i class="fab fa-twitter fa-fw" aria-hidden="true"></i>
                            <span class="d-none d-md-inline">Follow @jai_shan</span>
                        </a>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="team-member text-center mb-3">
                        <img src="{{ asset('frontend/images/team/team-3.png') }}" alt="Team Member 1"
                            class="img-fluid rounded-circle mb-3">
                        <h5 class="mb-2">Sam Carlos</h5>
                        <p class="mb-1">CEO</p>
                        <a href="https://twitter.com/jai_shan" target="_blank"
                            class="btn btn-primary btn-sm rounded-pill mt-3"
                            style="background-color: #1DA1F2; border-color: #1DA1F2;">
                            <i class="fab fa-twitter fa-fw" aria-hidden="true"></i>
                            <span class="d-none d-md-inline">Follow @jai_shan</span>
                        </a>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="team-member text-center mb-3">
                        <img src="{{ asset('frontend/images/team/team-4.png') }}" alt="Team Member 1"
                            class="img-fluid rounded-circle mb-3">
                        <h5 class="mb-2">Natalia portman</h5>
                        <p class="mb-1">CEO</p>
                        <a href="https://twitter.com/jai_shan" target="_blank"
                            class="btn btn-primary btn-sm rounded-pill mt-3"
                            style="background-color: #1DA1F2; border-color: #1DA1F2;">
                            <i class="fab fa-twitter fa-fw" aria-hidden="true"></i>
                            <span class="d-none d-md-inline">Follow @jai_shan</span>
                        </a>
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
