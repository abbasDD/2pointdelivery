@extends('frontend.layouts.app')

@section('title', 'Track Booking')

@section('content')

    <section class="section">
        <div class="container mb-5 p-3">
            <h5>Track Booking</h5>
            {{-- Tracking Form  --}}
            <form action="{{ route('trackBooking') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-9">
                        <input class="form-control" type="text" name="tracking_code" id="tracking_code"
                            placeholder="Enter tracking number" value="{{ isset($booking) ? $booking->uuid : '' }}"
                            required>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">Track</button>
                    </div>
                    {{-- Show error for invalid order --}}
                    @if (!isset($booking))
                        <div class="col-md-12">
                            <p class="text-danger m-2"> Please enter valid order </p>
                        </div>
                    @endif
                </div>
            </form>
            {{-- Order Detail Section  --}}
            <section class="py-3">
                <div class="container">
                    @if (isset($booking) && isset($bookingPayment))
                        <div class="row">
                            <div class="col-md-4">
                                {{-- Load Tracking Status --}}
                                @include('frontend.bookings.partials.show.tracking')
                            </div>
                            <div class="col-md-8">
                                {{-- Map Tracking --}}
                                @include('frontend.bookings.partials.show.map')
                            </div>
                        </div>
                    @endif
                </div>
            </section>

        </div>
    </section>
@endsection
