@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@section('content')

    {{-- Refund Wallet List --}}
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Refund Requests</h5>
        </div>
        @include('admin.wallet.list')
    </div>

@endsection
