@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@section('content')

    {{-- Received Wallet List --}}
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Received Requests</h5>
        </div>
        @include('admin.wallet.list')
    </div>

@endsection
