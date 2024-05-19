@extends('admin.layouts.app')

@section('title', 'KYC Details')

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-3">
        <h3 class="mb-0">KYC Details</h3>
    </div>

    @include('admin.kycDetails.partials.list')

@endsection
