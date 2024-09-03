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

                @if ($booking->status == 'draft' || $booking->status == 'pending')
                    <p class="badge bg-warning">{{ $booking->status }}</p>
                @elseif ($booking->status == 'accepted' || $booking->status == 'in_transit' || $booking->status == 'completed')
                    <p class="badge bg-success">{{ $booking->status }}</p>
                @else
                    <p class="badge bg-danger">{{ $booking->status }}</p>
                @endif

            </div>
    </section>


    {{-- Order Detail Section  --}}
    <section class="py-3">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    {{-- Load Tracking Status --}}
                    @include('admin.bookings.partials.show.tracking')

                    {{-- Load Images --}}
                    @include('admin.bookings.partials.show.images')
                </div>
                <div class="col-md-8">
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
