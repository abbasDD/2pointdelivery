@extends('helper.layouts.app')

@section('title', 'Helper Dashboard')

@section('content')


    {{-- Ask user to complete profile --}}
    @if (!$helperUpdated)
        <div class="alert alert-danger d-flex align-items-center justify-content-between" role="alert">
            <p class="m-0">Your profile is incomplete. Please complete it to accept your first booking.</p>
            <p class="m-0"><a href="{{ route('helper.profile') }}" class="btn btn-primary btn-sm ml-2">Complete</a>
            </p>
        </div>
    @else
        {{-- Prroval in Progress --}}
        @if ($helper->is_approved != 1 || $helperVehicle->is_approved != 1)
            <div class="alert alert-danger d-flex align-items-center justify-content-between" role="alert">
                <p class="m-0">Admin is reviewing your profile. Please wait.</p>
                <p class="m-0"><a href="{{ route('helper.chat.admin') }}" class="btn btn-primary btn-sm ml-2">Chat
                        Admin</a>
                </p>
            </div>
        @endif
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
                            <h5 class="card-title">{{ $satistics['accepted_bookings'] }}</h5>
                            <p class="card-text">Accepted Bookings</p>
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
                            <h5 class="card-title">{{ $satistics['cancelled_bookings'] }}</h5>
                            <p class="card-text">Completed Bookings</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card mb-1">
                    <div class="card-body d-flex align-items-center justify-content-between p-4">
                        <div class="">
                            <i class="fas fa-dollar fa-3x text-success"></i>
                        </div>
                        <div class="">
                            <h5 class="card-title">{{ $satistics['total_earnings'] }}</h5>
                            <p class="card-text">Total Earnings</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Pending Bookings  --}}
    <div class="recent-orders">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Pending Bookings</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            @include('helper.bookings.partials.list')


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

<script>
    function acceptBooking(id) {
        alert(id);
    }
</script>
