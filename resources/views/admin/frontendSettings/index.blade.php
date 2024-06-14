@extends('admin.layouts.app')

@section('title', 'Front End Settings')

@section('content')

    @trixassets

    <section class="section p-0">
        <div class="container-fluid">
            <div class="section-header mb-2">
                <div class="d-flex justify-content-between">
                    <h4 class="mb-0">Front End Settings</h4>
                </div>
            </div>
            <div class="section-body">
                <div id="clientTable">
                    @include('admin.frontendSettings.partials.tabs')
                </div>
            </div>
        </div>
    </section>

@endsection
