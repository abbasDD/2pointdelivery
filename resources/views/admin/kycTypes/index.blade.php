@extends('admin.layouts.app')

@section('title', 'KYC Types')

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-3">
        <h3 class="mb-0">KYC Types</h3>
        <a href="{{ route('admin.kycType.create') }}" class="btn btn-primary btn-sm">Add New</a>
    </div>

    @include('admin.kycTypes.partials.list')

@endsection
