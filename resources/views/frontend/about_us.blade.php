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
                        <h6>+ 7800 Successful deliveries</h6>
                        <h2>Our Story</h2>
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
                        <div class="mb-3">
                            <h3 class="text-primary mb-1">2009</h3>
                            <p>Established</p>
                        </div>
                        <div class="mb-2">
                            <h3 class="text-primary mb-1">7500 +</h3>
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
                        <h6>Our Mission</h6>
                        <h2>Our Mission and Goals</h2>
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
    @include('frontend.includes.ctahelp')

    {{-- Team Section  --}}
    @include('frontend.includes.team')

    {{-- Get Apps Section  --}}
    @include('frontend.includes.getapps')


@endsection
