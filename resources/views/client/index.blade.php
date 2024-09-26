@extends('client.layouts.app')

@section('title', 'Client Dashboard')

@section('content')

    {{-- Ask user to complete profile --}}
    @if (!$client_updated)
        <div class="alert alert-danger d-flex align-items-center justify-content-between" role="alert">
            <p class="m-0">Your profile is incomplete. Please complete it to book your first service.</p>
            <p class="m-0"><a href="{{ route('client.profile') }}" class="btn btn-primary btn-sm ml-2">Complete</a>
            </p>
        </div>
    @endif

    {{-- Ask user to book first service if profile is complete --}}
    @if ($client_updated && $satistics['total_bookings'] == 0)
        <div class="alert alert-success d-flex align-items-center justify-content-between" role="alert">
            <p class="m-0">You haven't embarked on your journey with us yet. Book your first service.</p>
            <p class="m-0"><a href="{{ route('newBooking') }}" class="btn btn-primary ml-2">Book Now</a>
            </p>
        </div>
    @endif

    {{-- Overall Statistics  --}}
    <div class="statistics">
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="card mb-1">
                    <div class="card-body d-flex align-items-center justify-content-between p-4">
                        <div class="">
                            <i class="fas fa-dolly fa-3x text-primary"></i>
                        </div>
                        <div class="">
                            <h5 class="card-title">{{ $satistics['total_bookings'] }}</h5>
                            <p class="card-text">Total Bookings</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card mb-1">
                    <div class="card-body d-flex align-items-center justify-content-between p-4">
                        <div class="">
                            <i class="fas fa-dolly fa-3x text-warning"></i>
                        </div>
                        <div class="">
                            <h5 class="card-title">{{ $satistics['pending_bookings'] }}</h5>
                            <p class="card-text">Pending Bookings</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card mb-1">
                    <div class="card-body d-flex align-items-center justify-content-between p-4">
                        <div class="">
                            <i class="fas fa-dolly fa-3x text-danger"></i>
                        </div>
                        <div class="">
                            <h5 class="card-title">{{ $satistics['cancelled_bookings'] }}</h5>
                            <p class="card-text">Cancelled Bookings</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card mb-1">
                    <div class="card-body d-flex align-items-center justify-content-between p-4">
                        <div class="">
                            <i class="fas fa-dolly fa-3x text-success"></i>
                        </div>
                        <div class="">
                            <h5 class="card-title">{{ $satistics['unpaid_bookings'] }}</h5>
                            <p class="card-text">Unpaid Bookings</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Bookings  --}}
    <div class="recent-orders">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Recent Bookings</h5>
                        {{-- New Booking --}}
                        <a href="{{ route('newBooking') }}" class="btn btn-primary btn-sm float-right">New Booking</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            @include('client.bookings.partials.list')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
