@extends('admin.layouts.app')

@section('title', 'Service Categories')

@section('content')

    <section class="section">
        <div class="container">
            <div class="section-header mb-2">
                <div class="d-flex justify-content-between">
                    <h4>Edit Service Category</h4>
                </div>
            </div>
            <div class="section-body">
                <form action="{{ route('admin.serviceType.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $serviceType->id }}">
                    @include('admin.service_types.form')
                </form>

            </div>
        </div>
    </section>

@endsection