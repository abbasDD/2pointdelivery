@extends('admin.layouts.app')

@section('title', 'Add Vehicle')

@section('content')

    <section class="section p-0">
        <div class="container">
            <div class="section-header mb-2">
                <div class="d-flex justify-content-between">
                    <h4>Add Vehicle Type</h4>
                </div>
            </div>
            <div class="section-body">
                <form action="{{ route('admin.vehicleType.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @include('admin.vehicle_types.form')
                </form>

            </div>
        </div>
    </section>

@endsection
