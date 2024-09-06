@extends('admin.layouts.app')

@section('title', 'Helpers')

@section('content')

    <section class="section p-0">
        <div class="container-fluid">
            <div class="section-header mb-2">
                <div class="d-flex justify-content-between">
                    <h4 class="mb-0">Helpers</h4>
                    <a href="{{ route('admin.helper.create') }}" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> Add
                        Helper</a>
                </div>
            </div>
            <div class="section-body">
                <div id="helperTable">
                    @include('admin.helpers.partials.list')
                </div>
            </div>
        </div>
    </section>


@endsection
