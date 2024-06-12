@extends('admin.layouts.app')

@section('title', 'Service Categories')

@section('content')

    <section class="section p-0">
        <div class="container">
            <div class="section-header mb-2">
                <div class="d-flex justify-content-between">
                    <h4>Add Service Category</h4>
                </div>
            </div>
            <div class="section-body">
                <form action="{{ route('admin.serviceCategory.store') }}" method="POST">
                    @csrf
                    @include('admin.service_categories.form')
                </form>

            </div>
        </div>
    </section>

@endsection
