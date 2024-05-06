@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@section('content')


    {{-- Loading Chat JS Library --}}

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- Overall Statistics  --}}
    <div class="statistics">
        <div class="row">
            {{-- Delivery Statistics --}}
            <div class="col-lg-3 col-md-6 mb-4">
                <h6>Delivery Statistics</h6>
                <div class="row">
                    <div class="col-12">
                        {{-- Successful Delivery Card --}}
                        <div class="card mb-3">
                            <div class="card-body d-flex align-items-center justify-content-between py-3 px-5">
                                <div class="bg-light-gray text-primary p-3 rounded-circle">
                                    <i class="fas fa-car fa-2x text-primary"></i>
                                </div>
                                <div class="text-right">
                                    <h5 class="card-title">5.5K</h5>
                                    <p class="card-text">Successful Delivery</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        {{-- Pending Delivery Card --}}
                        <div class="card mb-3">
                            <div class="card-body d-flex align-items-center justify-content-between py-3 px-5">
                                <div class="bg-light-gray text-primary p-3 rounded-circle">
                                    <i class="fas fa-car fa-2x text-warning"></i>
                                </div>
                                <div class="text-right">
                                    <h5 class="card-title">224</h5>
                                    <p class="card-text">Pending Delivery</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        {{-- Cancelled Delivery Card --}}
                        <div class="card mb-3">
                            <div class="card-body d-flex align-items-center justify-content-between py-3 px-5">
                                <div class="bg-light-gray text-primary p-3 rounded-circle">
                                    <i class="fas fa-car fa-2x text-danger"></i>
                                </div>
                                <div class="text-right">
                                    <h5 class="card-title">125</h5>
                                    <p class="card-text">Cancelled Delivery</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Moving Statistics --}}
            <div class="col-lg-3 col-md-6 mb-4">
                <h6>Moving Statistics</h6>
                <div class="row">
                    <div class="col-12">
                        {{-- Successful Moving Card --}}
                        <div class="card mb-3">
                            <div class="card-body d-flex align-items-center justify-content-between py-3 px-5">
                                <div class="bg-light-gray text-primary p-3 rounded-circle">
                                    <i class="fas fa-truck fa-2x text-primary"></i>
                                </div>
                                <div class="text-right">
                                    <h5 class="card-title">6.1K</h5>
                                    <p class="card-text">Successful Moving</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        {{-- Pending Moving Card --}}
                        <div class="card mb-3">
                            <div class="card-body d-flex align-items-center justify-content-between py-3 px-5">
                                <div class="bg-light-gray text-primary p-3 rounded-circle">
                                    <i class="fas fa-truck fa-2x text-warning"></i>
                                </div>
                                <div class="text-right">
                                    <h5 class="card-title">245</h5>
                                    <p class="card-text">Pending Moving</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        {{-- Cancelled Moving Card --}}
                        <div class="card mb-3">
                            <div class="card-body d-flex align-items-center justify-content-between py-3 px-5">
                                <div class="bg-light-gray text-primary p-3 rounded-circle">
                                    <i class="fas fa-truck fa-2x text-danger"></i>
                                </div>
                                <div class="text-right">
                                    <h5 class="card-title">152</h5>
                                    <p class="card-text">Cancelled Moving</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Delivery & Moving Statistics Graph --}}
            <div class="col-lg-6">
                <h6>Delivery & Moving Statistics</h6>
                <div class="card">
                    <div class="card-body">
                        <canvas id="deliveryMovingChart"></canvas>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Helper Tracking & Top Helpers --}}
    <div class="row">
        <div class="col-md-8">
            <h6>Helper Tracking</h6>
            <div class="card">
                <div class="card-body">
                    <div id="map" style="height:400px; width:100%;"></div>
                    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD-jXtk8qCpcwUwFn-7Q3VazeneJJ46g00&callback=initMap" async
                        defer></script>
                    <script>
                        function initMap() {
                            var map = new google.maps.Map(document.getElementById('map'), {
                                center: {
                                    lat: 33.6487,
                                    lng: 73.0407
                                },
                                zoom: 10
                            });

                            // Define some locations
                            var locations = [{
                                    lat: 33.6844,
                                    lng: 73.0489,
                                    title: "Driver 01"
                                }, // Example location
                                {
                                    lat: 33.6778,
                                    lng: 73.0300,
                                    title: "Driver 02"
                                }, // Example location
                                {
                                    lat: 33.6865,
                                    lng: 73.0962,
                                    title: "Driver 03"
                                } // Example location
                            ];

                            // Create markers for each location
                            locations.forEach(function(location) {
                                var marker = new google.maps.Marker({
                                    position: {
                                        lat: location.lat,
                                        lng: location.lng
                                    },
                                    map: map,
                                    title: location.title
                                });
                            });
                        }
                    </script>
                </div>

            </div>
        </div>
        <div class="col-md-4">
            {{-- List of Top Helpers --}}
            <h6>Top Helpers</h6>
            <div class="card">
                <div class="card-body">
                    @foreach ($topHelpers as $topHelper)
                        {{-- Top Helper Item --}}
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="d-flex align-items-center">
                                <img class="rounded mr-3" src="{{ $topHelper['image'] }}" alt="">
                                <div class="info">
                                    <h6 class="mb-0">{{ $topHelper['name'] }}</h6>
                                    <p class="mb-0">{{ $topHelper['email'] }}</p>
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
    </div>

    {{-- Loading Data to Chart from Blade --}}
    <script>
        var ctx = document.getElementById('deliveryMovingChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartData['labels']),
                datasets: [{
                    label: 'Delivery',
                    data: @json($chartData['delivery']),
                    borderColor: 'rgba(0, 73, 56, 1)',
                    borderWidth: 2,
                    fill: false,
                    tension: 0.5,
                    pointBackgroundColor: 'rgba(0, 73, 56, 1)',
                    pointStyle: 'crossRot'
                }, {
                    label: 'Moving',
                    data: @json($chartData['moving']),
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
