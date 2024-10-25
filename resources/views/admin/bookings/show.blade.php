@extends('admin.layouts.app')

@section('title', 'Booking Detail')

@section('content')

    {{-- Header Section  --}}
    <section class="py-3">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between">
                <div class="">
                    <h3 class="mb-1">Booking Detail</h3>
                    <p>Reference No : <span class="text-uppercase">{{ $booking->uuid ? $booking->uuid : '-' }}</span></p>
                </div>

                <div class="">
                    <a class="btn btn-success" href="{{ route('booking-invoice-pdf', $booking->id) }}" target="_blank"><i
                            class="fa fa-file" aria-hidden="true"></i> <span class="d-none d-md-inline">
                            Invoice</span></a>
                    <a href="{{ route('label', $booking->id) }}" class="btn btn-success" target="_blank"><i
                            class="fa fa-file" aria-hidden="true"></i>
                        <span class="d-none d-md-inline">
                            Label</span></a>
                </div>
            </div>
    </section>

    {{-- Order Detail Section  --}}
    <section class="py-3">
        <div class="container">
            <div class="row">
                @if ($booking->booking_type == 'secureship')
                    {{-- @include('admin.bookings.partials.show.secureship') --}}
                @else
                    <div class="col-md-4">
                        {{-- Load Tracking Status --}}
                        @include('admin.bookings.partials.show.tracking')

                        {{-- Load Images --}}
                        @include('admin.bookings.partials.show.images')
                    </div>
                @endif
                <div class="{{ $booking->booking_type == 'secureship' ? 'col-md-12' : 'col-md-8' }}">
                    {{-- Map Tracking --}}
                    @include('admin.bookings.partials.show.map')

                    <div class="card mb-3">
                        <div class="card-body">
                            {{-- Include Order Summary from admin\bookings\partials\info.blade.php --}}
                            @include('admin.bookings.partials.info')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


@endsection
