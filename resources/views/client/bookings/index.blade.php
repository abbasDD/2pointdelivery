@extends('client.layouts.app')

@section('title', 'Bookings')

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-3">
        <h3 class="mb-0">Bookings</h3>
        {{-- Create New Booking --}}
        <a href="{{ route('newBooking') }}" class="btn btn-primary btn-sm">New Booking</a>
    </div>

    @include('client.bookings.partials.list')
@endsection
