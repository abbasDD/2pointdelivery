@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@section('content')


    {{-- Loading Chat JS Library --}}

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- Overall Statistics  --}}
    <div class="statistics">
        <div class="row">
            {{-- Booking Statistics --}}
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        {{-- Heading --}}
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="card-title">Booking Statistics</h5>
                            <i class="fas fa-dolly fa-2x text-success"></i>
                        </div>
                        <hr>
                        {{-- Total Bookings --}}
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <p class="mb-0 fs-14"><i class="fa fa-shopping-bag text-success"></i> Total Bookings</p>
                            <h6 class="mb-0">{{ $statistics['total_bookings'] }}</h6>
                        </div>
                        {{-- Successful Booking --}}
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <p class="mb-0 fs-14"><i class="fa fa-check-circle text-success"></i> Successful Booking</p>
                            <h6 class="mb-0">{{ $statistics['successful_bookings'] }}</h6>
                        </div>
                        {{-- Pending Booking --}}
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <p class="mb-0 fs-14"><i class="fa fa-spinner text-warning"></i> Pending Booking</p>
                            <h6 class="mb-0">{{ $statistics['pending_bookings'] }}</h6>
                        </div>
                        {{-- Canceled Booking --}}
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <p class="mb-0 fs-14"><i class="fa fa-times-circle text-danger"></i> Canceled Booking</p>
                            <h6 class="mb-0">{{ $statistics['cancelled_bookings'] }}</h6>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Users Statistics --}}
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        {{-- Heading --}}
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="card-title">Users Statistics</h5>
                            <i class="fas fa-users fa-2x text-primary"></i>
                        </div>
                        <hr>
                        {{-- Total Users --}}
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <p class="mb-0 fs-14"><i class="fa fa-user text-success"></i> Total Users</p>
                            <h6 class="mb-0">{{ $statistics['total_users'] }}</h6>
                        </div>
                        {{-- Total Clients --}}
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <p class="mb-0 fs-14"><i class="fa-solid fa-mug-hot text-primary"></i> Total Clients</p>
                            <h6 class="mb-0">{{ $statistics['total_clients'] }}</h6>
                        </div>
                        {{-- Total Helpers --}}
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <p class="mb-0 fs-14"><i class="fa-solid fa-motorcycle text-warning"></i> Total Helpers</p>
                            <h6 class="mb-0">{{ $statistics['total_helpers'] }}</h6>
                        </div>
                        {{-- Requested Helpers --}}
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <p class="mb-0 fs-14"><i class="fa-solid fa-question text-danger"></i> Requested Helpers</p>
                            <h6 class="mb-0">{{ $statistics['requested_helpers'] }}</h6>

                        </div>
                    </div>
                </div>
            </div>
            {{-- Earning Statistics --}}
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        {{-- Heading --}}
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="card-title">Earning Statistics</h5>
                            <i class="fas fa-dollar-sign fa-2x text-success"></i>
                        </div>
                        <hr>
                        {{-- Total Earning --}}
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <p class="mb-0 fs-14"><i class="fa fa-dollar-sign text-success"></i> Total Earning</p>
                            <h6 class="mb-0">${{ $statistics['total_earnings'] }}</h6>
                        </div>
                        {{-- total_commission --}}
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <p class="mb-0 fs-14"><i class="fa fa-dollar-sign text-success"></i> Total Commission</p>
                            <h6 class="mb-0">${{ $statistics['total_commission'] }}</h6>
                        </div>
                        {{-- total_taxes --}}
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <p class="mb-0 fs-14"><i class="fa fa-dollar-sign text-success"></i> Total Taxes</p>
                            <h6 class="mb-0">${{ $statistics['total_taxes'] }}</h6>
                        </div>
                        {{-- total_revenue --}}
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <p class="mb-0 fs-14"><i class="fa fa-dollar-sign text-success"></i> Total Revenue</p>
                            <h6 class="mb-0">${{ $statistics['total_revenue'] }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                {{-- Helpers Request --}}
                <div class="card">
                    <div class="card-body">
                        {{-- Heading --}}
                        <h6>Helpers Request</h6>
                        {{-- Helper List --}}
                        @foreach ($requestedHelpers as $requestedHelper)
                            {{-- Top Helper Item --}}
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="d-flex align-items-center">
                                    <img class="rounded mr-3" src="{{ $requestedHelper['image'] }}" alt="">
                                    <div class="info">
                                        <h6 class="mb-0">{{ $requestedHelper['name'] }}</h6>
                                        <p class="mb-0">{{ $requestedHelper['email'] }}</p>
                                    </div>
                                </div>
                                <div class="action">
                                    {{-- <a href="#" class="fs-xxs text-primary">View </a> --}}
                                    <button class="btn btn-sm btn-primary">View</button>
                                </div>
                            </div>
                            <hr class="mt-1">
                        @endforeach
                    </div>
                </div>
            </div>
            {{-- Delivery & Moving Statistics Graph --}}
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        {{-- Heading --}}
                        <h6>Delivery & Moving Statistics</h6>

                        {{-- Delivery Moving Chart --}}
                        <canvas id="deliveryMovingChart"></canvas>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Latest Bookings & Helper Requests --}}
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    {{-- Heading --}}
                    <h6>Latest Bookings</h6>

                    {{-- Booking List --}}
                    <table class="table table-striped">
                        <thead class="thead-primary">
                            <tr>
                                <th>ID</th>
                                <th>Client</th>
                                <th>Priority</th>
                                <th>Service Type</th>
                                <th>Address</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($latestBookings as $booking)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $booking->client->first_name }}</td>
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
                                        <p
                                            class="badge {{ $booking->status == 'completed' ? 'bg-primary' : 'bg-danger' }}">
                                            {{ $booking->status }}
                                        </p>
                                    </td>
                                    <td><a href="{{ route('admin.booking.show', 1) }}" class="btn btn-sm btn-primary"><i
                                                class="fas fa-eye"></i></a></td>
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

    {{-- Loading Data to Chart from Blade --}}
    <script>
        var ctx = document.getElementById('deliveryMovingChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($lastSixMonths['labels']),
                datasets: [{
                    label: 'Delivery',
                    data: @json($lastSixMonths['delivery']),
                    borderColor: 'rgba(0, 73, 56, 1)',
                    borderWidth: 2,
                    fill: false,
                    tension: 0.5,
                    pointBackgroundColor: 'rgba(0, 73, 56, 1)',
                    pointStyle: 'crossRot'
                }, {
                    label: 'Moving',
                    data: @json($lastSixMonths['moving']),
                    borderColor: 'rgba(50, 170, 42, 1)',
                    borderWidth: 2,
                    fill: false,
                    tension: 0.5,
                    pointBackgroundColor: 'rgba(50, 170, 42, 1)',
                    pointStyle: 'cross'
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                    }
                }
            }
        });
    </script>

@endsection
