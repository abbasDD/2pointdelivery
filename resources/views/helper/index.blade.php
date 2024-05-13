@extends('helper.layouts.app')

@section('title', 'Helper Dashboard')

@section('content')

    {{-- Ask user to complete profile --}}
    @if (!Auth::user()->is_updated)
        <div class="alert alert-danger d-flex align-items-center justify-content-between" role="alert">
            <p class="m-0">Your profile is incomplete. Please complete it to accept your first booking.</p>
            <p class="m-0"><a href="{{ route('helper.edit') }}" class="btn btn-primary ml-2">Complete</a>
            </p>
        </div>
    @endif
    {{-- Overall Statistics  --}}
    <div class="statistics">
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card">
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
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card">
                    <div class="card-body d-flex align-items-center justify-content-between p-4">
                        <div class="">
                            <i class="fas fa-dolly fa-3x text-warning"></i>
                        </div>
                        <div class="">
                            <h5 class="card-title">{{ $satistics['pending_bookings'] }}</h5>
                            <p class="card-text">Accepted Bookings</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card">
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
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card">
                    <div class="card-body d-flex align-items-center justify-content-between p-4">
                        <div class="">
                            <i class="fas fa-dollar fa-3x text-success"></i>
                        </div>
                        <div class="">
                            <h5 class="card-title">{{ $satistics['unpaid_bookings'] }}</h5>
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
                            <table class="table table-striped">
                                <thead class="thead-primary">
                                    <tr>
                                        <th>ID</th>
                                        {{-- <th>Client</th> --}}
                                        <th>Priority</th>
                                        <th>Service Type</th>
                                        <th>Address</th>
                                        <th>Price</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($bookings as $booking)
                                        <tr>

                                            <td>{{ $loop->index + 1 }}</td>
                                            {{-- <td>{{ $booking->client->first_name }}</td> --}}
                                            <td>{{ $booking->prioritySetting->name }}</td>
                                            <td>
                                                {{-- Service Type --}}
                                                <p>{{ $booking->serviceType->name }}</p>
                                                {{-- Service Category --}}
                                                <p>{{ $booking->serviceCategory->name }}</p>
                                            </td>
                                            <td>
                                                {{-- Pickup Address --}}
                                                <p>Pickup: {{ $booking->pickup_address }}</p>
                                                {{-- Dropoff Address --}}
                                                <p>Dropoff: {{ $booking->dropoff_address }}</p>
                                            </td>
                                            <td>{{ $booking->total_price }}</td>
                                            <td>
                                                <a class="btn btn-sm btn-primary"
                                                    href="{{ route('helper.booking.accept', $booking->id) }}">Accept</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">No data found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
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
