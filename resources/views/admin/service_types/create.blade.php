@extends('admin.layouts.app')

@section('title', 'Service Types')

@section('content')

    <section class="section">
        <div class="container">
            <div class="section-header mb-2">
                <div class="d-flex justify-content-between">
                    <h4>Add Service Type</h4>
                </div>
            </div>
            <div class="section-body">
                <form action="{{ route('admin.serviceType.store') }}" method="POST">
                    @csrf
                    @include('admin.service_types.form')
                </form>

            </div>
        </div>
    </section>

@endsection
