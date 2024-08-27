@extends('helper.layouts.app')

@section('title', 'Helper Dashboard')

@section('content')

    {{-- Wallet Balance --}}
    <div class="row">
        {{-- Available Balance --}}
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <p class="mb-0">Available Balance</p>
                        <i class="fas fa-wallet text-success"></i>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <h3 class="mb-0">$ {{ $balance['available'] }}</h3>
                        @if ($helperBankAccounts && $balance['available'])
                            <button class="btn btn-primary btn-sm" onclick="openWithdrawModal()">Withdraw</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        {{-- Total Balance --}}
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <p class="mb-0">Total Balance</p>
                        <i class="fas fa-wallet text-primary"></i>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <h3 class="mb-0">$ {{ $balance['total'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
        {{-- Withdrawn Balance --}}
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <p class="mb-0">Withdrawn Balance</p>
                        <i class="fas fa-wallet text-danger"></i>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <h3 class="mb-0">$ {{ $balance['withdrawn'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Bank Accounts --}}
    @include('helper.wallet.bank-accounts')

    {{-- openWithdrawModal --}}
    @include('helper.wallet.withdraw-modal')

@endsection
