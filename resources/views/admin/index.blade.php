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
                        {{-- Total Admins --}}
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <p class="mb-0 fs-14"><i class="fa fa-user text-success"></i> Total Admins</p>
                            <h6 class="mb-0">{{ $statistics['total_admins'] }}</h6>
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
                        {{-- New Helpers --}}
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <p class="mb-0 fs-14"><i class="fa-solid fa-question text-danger"></i> New Helpers</p>
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
                            <h6 class="mb-0">${{ number_format($statistics['total_earnings'], 0, '.', '') }}</h6>
                        </div>
                        {{-- Total Payments --}}
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <p class="mb-0 fs-14"><i class="fa fa-dollar-sign text-success"></i> Total Payments</p>
                            <h6 class="mb-0">${{ number_format($statistics['total_payments'], 0, '.', '') }}</h6>
                        </div>
                        {{-- Total Taxes --}}
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <p class="mb-0 fs-14"><i class="fa fa-dollar-sign text-success"></i> Total Taxes</p>
                            <h6 class="mb-0">${{ number_format($statistics['total_taxes'], 0, '.', '') }}</h6>
                        </div>
                        {{-- Total Revenue --}}
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <p class="mb-0 fs-14"><i class="fa fa-dollar-sign text-success"></i> Total Revenue</p>
                            <h6 class="mb-0">${{ number_format($statistics['total_revenue'], 0, '.', '') }}</h6>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                {{-- New Helpers --}}
                <div class="card mb-2">
                    <div class="card-body">
                        {{-- Heading --}}
                        <h5 class="card-title">New Helpers</h5>
                        <div class="scroll-div" style="height: 200px">
                            {{-- Helper List --}}
                            @forelse ($helperRequests as $helperRequest)
                                {{-- Top Helper Item --}}
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="d-flex align-items-center">
                                        <img class="rounded mr-1"
                                            src="{{ $helperRequest['profile_image'] ? asset('images/users/' . $helperRequest['profile_image']) : asset('images/users/default.png') }}"
                                            width="35" height="35" alt="profile">
                                        <div class="info">
                                            <h6 class="mb-0">
                                                {{ $helperRequest['first_name'] . ' ' . $helperRequest['last_name'] }}</h6>
                                            <p class="mb-0 fs-xxs">{{ $helperRequest['email'] }}</p>
                                        </div>
                                    </div>
                                    <div class="action">
                                        {{-- <a href="#" class="fs-xxs text-primary">View </a> --}}
                                        <a href="{{ route('admin.helper.show', $helperRequest['id']) }}"
                                            class="btn btn-sm btn-primary"><i class="fa fa-eye"></i></a>
                                    </div>
                                </div>
                                <hr class="mt-1">
                            @empty
                                <div class="text-center">
                                    <p>No Requests Available</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
                {{-- New Registrations --}}
                <div class="card">
                    <div class="card-body">
                        {{-- Heading --}}
                        <h5 class="card-title">New Registrations</h5>
                        <div class="scroll-div" style="height: 200px">
                            {{-- Helper List --}}
                            @forelse ($newRegisteredUsers as $newRegisteredUser)
                                {{-- Top Helper Item --}}
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="d-flex align-items-center">
                                        <img class="rounded mr-1"
                                            src="{{ $newRegisteredUser['profile_image'] ? asset('images/users/' . $newRegisteredUser['profile_image']) : asset('images/users/default.png') }}"
                                            width="35" height="35" alt="profile">
                                        <div class="info">
                                            @if ($newRegisteredUser['helper_enabled'])
                                                <h6 class="mb-0">Helper</h6>
                                            @else
                                                <h6 class="mb-0">Client</h6>
                                            @endif
                                            <p class="mb-0 fs-xxs">{{ $newRegisteredUser['email'] }}</p>
                                        </div>
                                    </div>
                                    <div class="action">
                                        {{-- <a href="#" class="fs-xxs text-primary">View </a> --}}
                                        <a href="{{ route('admin.helper.show', $newRegisteredUser['id']) }}"
                                            class="btn btn-sm btn-primary">View</a>
                                    </div>
                                </div>
                                <hr class="mt-1">
                            @empty
                                <div class="text-center">
                                    <p>No Requests Available</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            {{-- Delivery & Moving Statistics Graph --}}
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        {{-- Heading --}}
                        <h5 class="card-title">Delivery & Moving Statistics</h5>

                        {{-- Delivery Moving Chart --}}
                        <canvas id="deliveryMovingChart" class="my-3"></canvas>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Latest Bookings --}}
    <div class="row">
        {{-- Latest Bookings --}}
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    {{-- Heading --}}
                    <h5 class="card-title">Latest Bookings</h5>

                    {{-- Booking List --}}
                    <table class="table table-striped">
                        <thead class="thead-primary">
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
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
                                    <td>{{ app('dateHelper')->formatTimestamp($booking->created_at, 'Y-m-d') }}</td>
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
                                    <td><a href="{{ route('admin.booking.show', $booking->id) }}"
                                            class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></a></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">No data found</td>
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
                labels: @json($deliveryMovingChartData['labels']),
                datasets: [{
                    label: 'Delivery',
                    data: @json($deliveryMovingChartData['delivery']),
                    borderColor: 'rgba(0, 73, 56, 1)',
                    borderWidth: 2,
                    fill: false,
                    tension: 0.5,
                    pointBackgroundColor: 'rgba(0, 73, 56, 1)',
                    pointStyle: 'crossRot'
                }, {
                    label: 'Moving',
                    data: @json($deliveryMovingChartData['moving']),
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
