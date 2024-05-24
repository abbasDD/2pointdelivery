@extends('client.layouts.app')

@section('title', 'Address Books')

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-3">
        <h3 class="mb-0">Address Books</h3>
        {{-- Create New Booking --}}
        {{-- <a href="{{ route('client.addressBooks.create') }}" class="btn btn-primary btn-sm">Add New</a> --}}
    </div>

    @include('client.addressBooks.partials.list')
@endsection
