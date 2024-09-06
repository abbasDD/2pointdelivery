@extends('admin.layouts.app')

@section('title', 'Clients')

@section('content')

    <section class="section p-0">
        <div class="container-fluid">
            <div class="section-header mb-2">
                <div class="d-flex justify-content-between">
                    <h4 class="mb-0">Clients</h4>
                    <a href="{{ route('admin.client.create') }}" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> Add
                        Client</a>
                </div>
            </div>
            <div class="section-body">
                <div id="clientTable">
                    @include('admin.clients.partials.list')
                </div>
            </div>
        </div>
    </section>

@endsection
