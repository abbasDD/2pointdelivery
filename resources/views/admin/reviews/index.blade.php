@extends('admin.layouts.app')

@section('title', 'Booking Reviews')

@section('content')

    <section class="section p-0">
        <div class="container-fluid">
            <div class="section-header mb-2">
                <div class="d-flex justify-content-between">
                    <h4 class="mb-0">Booking Reviews</h4>
                </div>
            </div>
            <div class="section-body">
                <div id="clientTable">
                    @include('admin.reviews.partials.list')
                </div>
            </div>
        </div>
    </section>


@endsection
