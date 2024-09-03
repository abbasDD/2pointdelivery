@extends('helper.layouts.app')

@section('title', 'Booking Detail')

@section('content')

    {{-- Header Section  --}}
    <section class="py-3">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between">
                <div class="">
                    <h3 class="mb-1">Booking Detail</h3>
                    <p>Booking Reference : <span class="text-uppercase">{{ $booking->uuid ? $booking->uuid : '-' }}</span>
                    </p>
                </div>
                {{-- Action buttons --}}
                @include('frontend.bookings.partials.show.actions')
            </div>
    </section>


    {{-- Order Detail Section  --}}
    <section class="py-3">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    {{-- Load Tracking Status --}}
                    @include('frontend.bookings.partials.show.tracking')

                    {{-- Load Images --}}
                    @include('frontend.bookings.partials.show.images')
                </div>
                <div class="col-md-8">
                    {{-- Map Tracking --}}
                    @include('frontend.bookings.partials.show.map')

                    <div class="card mb-3">
                        <div class="card-body">
                            {{-- Include Order Summary from frontend\bookings\partials\info.blade.php --}}
                            @include('frontend.bookings.partials.info')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


@endsection
