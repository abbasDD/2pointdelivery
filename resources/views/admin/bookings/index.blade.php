@extends('admin.layouts.app')

@section('title', 'Bookings')

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-3">
        <h3>Bookings</h3>
    </div>
    <div class="dataTable">
        @include('admin.bookings.partials.list')
    </div>

@endsection
