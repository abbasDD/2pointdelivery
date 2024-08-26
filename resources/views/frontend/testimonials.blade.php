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
                {{-- Testimonial Overview --}}
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body text-center">
                            <h4>{{ $avg_rating }}
                                <i class="fas fa-star {{ $avg_rating >= 1 ? 'text-warning' : '' }}"></i>
                                <i class="fas fa-star {{ $avg_rating >= 2 ? 'text-warning' : '' }}"></i>
                                <i class="fas fa-star {{ $avg_rating >= 3 ? 'text-warning' : '' }}"></i>
                                <i class="fas fa-star {{ $avg_rating >= 4 ? 'text-warning' : '' }}"></i>
                                <i class="fas fa-star {{ $avg_rating >= 5 ? 'text-warning' : '' }}"></i>
                            </h4>
                            <p>{{ $avg_rating }} out of 5 stars based on {{ $total_reviews }} reviews</p>

                        </div>
                    </div>
                </div>
                {{-- Testimonials --}}
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            @forelse ($testimonials as $item)
                                {{-- Rating Star --}}
                                <p>
                                    <i class="fas fa-star {{ $item->rating >= 1 ? 'text-warning' : '' }}"></i>
                                    <i class="fas fa-star {{ $item->rating >= 2 ? 'text-warning' : '' }}"></i>
                                    <i class="fas fa-star {{ $item->rating >= 3 ? 'text-warning' : '' }}"></i>
                                    <i class="fas fa-star {{ $item->rating >= 4 ? 'text-warning' : '' }}"></i>
                                    <i class="fas fa-star {{ $item->rating >= 5 ? 'text-warning' : '' }}"></i>
                                </p>
                                {{-- Quotes Icon --}}
                                <i class="fas fa-quote-left mb-2"></i>
                                <p class="mb-3">{{ $item->review }}</p>

                                {{-- Client Name  --}}
                                <h5 class="text-capitalize"> - {{ $item->client_name }}</h5>

                                <hr>
                            @empty
                                <p>No reviews yet</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
    </section>



    {{-- Still looking for help ? --}}
    @include('frontend.includes.ctahelp')


@endsection
