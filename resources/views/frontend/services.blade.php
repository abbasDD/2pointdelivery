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
    @include('frontend.includes.services')


    {{-- Still looking for help ? --}}
    @include('frontend.includes.ctahelp')


    {{-- How It Works Section  --}}
    @include('frontend.includes.howitworks')


    {{-- Get Apps Section  --}}
    @include('frontend.includes.getapps')




@endsection
