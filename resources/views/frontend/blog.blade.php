@extends('frontend.layouts.app')

@section('title', 'Blogs')

@section('content')

    {{-- Header Section  --}}
    <section class="py-3 bg-light-gray">
        <div class="container my-5">
            <div class="row align-items-center">
                <div class="col-md-7 px-5">
                    <h2>Top Tips for a Smooth and Efficient Move: Expert Advice from <span class="text-primary">2 Point
                            Delivery</span> </h2>
                    <p>Whether you're planning a local move, a long-distance relocation, or simply need reliable delivery
                        services, our blog offers insightful articles to make your experience as smooth and stress-free as
                        possible. From packing hacks and moving checklists to choosing the right service provider, we've got
                        you covered. Stay tuned for regular updates and let us help you move with confidence!</p>
                </div>
                <div class="col-md-5 text-center">
                    <img src="{{ asset('frontend/images/services/service-bg.png') }}" alt="Image" class="img-fluid">
                </div>
            </div>
        </div>
    </section>

    {{-- Services Section  --}}
    @include('frontend.includes.blogs')


    {{-- Still looking for help ? --}}
    @include('frontend.includes.ctahelp')


@endsection
