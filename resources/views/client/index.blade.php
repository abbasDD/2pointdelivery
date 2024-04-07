@extends('client.layouts.app')

@section('title', 'Client Dashboard')

@section('content')

    {{-- Ask user to complete profile --}}
    @if (!Auth::user()->is_updated)
        <div class="alert alert-danger d-flex align-items-center justify-content-between" role="alert">
            <p class="m-0">Your profile is incomplete. Please complete it to book your first service.</p>
            <p class="m-0"><a href="{{ route('client.complete_profile') }}" class="btn btn-primary ml-2">Complete</a>
            </p>
        </div>
    @endif


    {{-- Overall Statistics  --}}
    <div class="statistics">
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card">
                    <div class="card-body d-flex align-items-center justify-content-between p-5">
                        <div class="">
                            <i class="fas fa-users fa-3x"></i>
                        </div>
                        <div class="">
                            <h5 class="card-title">5.5K</h5>
                            <p class="card-text">Total Orders</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card">
                    <div class="card-body d-flex align-items-center justify-content-between p-5">
                        <div class="">
                            <i class="fas fa-users fa-3x"></i>
                        </div>
                        <div class="">
                            <h5 class="card-title">5.5K</h5>
                            <p class="card-text">Total Orders</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card">
                    <div class="card-body d-flex align-items-center justify-content-between p-5">
                        <div class="">
                            <i class="fas fa-users fa-3x"></i>
                        </div>
                        <div class="">
                            <h5 class="card-title">5.5K</h5>
                            <p class="card-text">Total Orders</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card">
                    <div class="card-body d-flex align-items-center justify-content-between p-5">
                        <div class="">
                            <i class="fas fa-users fa-3x"></i>
                        </div>
                        <div class="">
                            <h5 class="card-title">5.5K</h5>
                            <p class="card-text">Total Orders</p>
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
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Pickup</th>
                                        <th scope="col">Dropoff</th>
                                        <th scope="col">Driver</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row">1</th>
                                        <td>Mark</td>
                                        <td>Otto</td>
                                        <td>@mdo</td>
                                        <td>Completed</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">2</th>
                                        <td>Jacob</td>
                                        <td>Thornton</td>
                                        <td>@fat</td>
                                        <td>Pending</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">3</th>
                                        <td>Harry</td>
                                        <td>the Bird</td>
                                        <td>@twitter</td>
                                        <td>Cancelled</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">4</th>
                                        <td>Garry</td>
                                        <td>the Bird</td>
                                        <td>@author</td>
                                        <td>Cancelled</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>

                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
