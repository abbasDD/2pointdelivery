@extends('helper.layouts.app')

@section('title', 'KYC Details')

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-3">
        <h3 class="mb-0">KYC Details</h3>
        {{-- Create New Booking --}}
        <a href="{{ route('helper.kyc.create') }}" class="btn btn-primary btn-sm">Add New</a>
    </div>

    @include('helper.kycDetails.partials.list')

@endsection
