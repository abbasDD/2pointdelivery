@extends('client.layouts.app')

@section('title', 'Client Dashboard')

@section('content')

    {{-- Client Wallet --}}
    <div class="row">
        {{-- Amount Spend --}}
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <p class="mb-0">Amount Spend</p>
                        <i class="fas fa-wallet text-success"></i>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <h3 class="mb-0">C$ {{ $balance['amount_spend'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
        {{-- Unpaid Amount --}}
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <p class="mb-0">Unpaid Amount</p>
                        <i class="fas fa-wallet text-primary"></i>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <h3 class="mb-0">C$ {{ $balance['unpaid_amount'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
        {{-- Amount Refunded --}}
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <p class="mb-0">Amount Refunded</p>
                        <i class="fas fa-wallet text-danger"></i>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <h3 class="mb-0">C$ {{ $balance['amount_refunded'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
