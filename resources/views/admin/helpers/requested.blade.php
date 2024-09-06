@extends('admin.layouts.app')

@section('title', 'Helpers')

@section('content')

    <section class="section p-0">
        <div class="container-fluid">
            <div class="section-header mb-2">
                <div class="d-flex justify-content-between">
                    <h4 class="mb-0">New Helpers</h4>
                </div>
            </div>
            <div class="section-body">
                <div id="helperTable">
                    @include('admin.helpers.partials.requested_list')
                </div>
            </div>
        </div>
    </section>


@endsection
