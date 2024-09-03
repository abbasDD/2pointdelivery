@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@section('content')

    {{-- Admin Wallet --}}
    <div class="row">
        {{-- Total Revenue --}}
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <p class="mb-0">Total Revenue</p>
                        <i class="fas fa-wallet text-warning"></i>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <h3 class="mb-0">C$ {{ $statistic['total_revenue'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
        {{-- Total Payments --}}
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <p class="mb-0">Total Payments</p>
                        <i class="fas fa-wallet text-success"></i>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <h3 class="mb-0">C$ {{ $statistic['total_payments'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
        {{-- Total Taxes --}}
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <p class="mb-0">Total Taxes</p>
                        <i class="fas fa-wallet text-danger"></i>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <h3 class="mb-0">C$ {{ $statistic['total_taxes'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
        {{-- Total Earnings --}}
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <p class="mb-0">Total Earnings</p>
                        <i class="fas fa-wallet text-primary"></i>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <h3 class="mb-0">C$ {{ $statistic['total_earnings'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Admin Wallet List --}}
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Wallet History</h5>
        </div>
        @include('admin.wallet.list')
    </div>

@endsection
