@extends('frontend.layouts.app')

@section('title', 'Testimonials')

@section('content')

    {{-- Header Section  --}}
    <section class=" bg-light-gray">
        <div class="container ">
            <div class="row align-items-center justify-content-center">
                <div class="col-md-7 p-5 text-center">
                    <h3>See what our customers are saying</h3>
                    <p>We are happy to see what our customers are saying about us and our services</p>
                    {{-- <a href="{{ route('helper.register') }}" class="btn btn-primary mt-3">Join as Helper</a> --}}
                    {{-- Redirect to Helper Register --}}
                    <div class="arrow-button">
                        <a href="{{ route('newBooking') }}">
                            <i class="fas fa-long-arrow-alt-right mr-2"></i> Book A Service
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="heading">
                        <h2>Testimonials</h2>
                    </div>
                    <div class="col-md-12">

                    </div>
                </div>
            </div>
    </section>



    {{-- Still looking for help ? --}}
    @include('frontend.includes.ctahelp')


@endsection
