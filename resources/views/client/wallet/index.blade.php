@extends('client.layouts.app')

@section('title', 'Client Dashboard')

@section('content')

    {{-- Client Wallet --}}
    <div class="row">
        {{-- Amount Spend --}}
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <p class="mb-0">Amount Spend</p>
                        <i class="fas fa-wallet text-success"></i>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <h3 class="mb-0">C$ {{ $statistic['amount_spend'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
        {{-- Unpaid Amount --}}
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <p class="mb-0">Unpaid Amount</p>
                        <i class="fas fa-wallet text-primary"></i>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <h3 class="mb-0">C$ {{ $statistic['unpaid_amount'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
        {{-- Cancelled Amount --}}
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <p class="mb-0">Cancelled Amount</p>
                        <i class="fas fa-wallet text-danger"></i>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <h3 class="mb-0">C$ {{ $statistic['cancelled_amount'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
        {{-- Amount Refunded --}}
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <p class="mb-0">Amount Refunded</p>
                        <i class="fas fa-wallet text-danger"></i>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <h3 class="mb-0">C$ {{ $statistic['amount_refunded'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- Client Wallet List --}}
    @include('client.wallet.list')

@endsection
