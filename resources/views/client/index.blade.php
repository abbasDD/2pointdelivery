@extends('client.layouts.app')

@section('title', 'Client Dashboard')

@section('content')

    {{-- Ask user to complete profile --}}
    @if (!Auth::user()->is_updated)
        <div class="alert alert-danger d-flex align-items-center justify-content-between" role="alert">
            <p class="m-0">Your profile is incomplete. Please complete it to book your first service.</p>
            <p class="m-0"><a href="{{ route('client.edit') }}" class="btn btn-primary ml-2">Complete</a>
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
                            <p class="card-text">Pending Bookings</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card">
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
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card">
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

    {{-- Recent Orders  --}}
    <div class="recent-orders">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Recent Orders</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead class="thead-primary">
                                    <tr>
                                        <th>ID</th>
                                        <th>Priority</th>
                                        <th>Pickup Address</th>
                                        <th>Dropoff Address</th>
                                        <th>Type</th>
                                        <th>Price</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($bookings as $booking)
                                        <tr>
                                            <td>{{ $booking->id }}</td>
                                            <td>{{ $booking->prioritySetting->name }}</td>
                                            <td>{{ $booking->pickup_address }}</td>
                                            <td>{{ $booking->dropoff_address }}</td>
                                            <td>{{ $booking->serviceType->name }}</td>
                                            <td>{{ $booking->total_price }}</td>
                                            <td>{{ $booking->status }}</td>
                                            <td><a href="{{ route('client.booking.show', $booking->id) }}"
                                                    class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></a></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center">No data found</td>
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
