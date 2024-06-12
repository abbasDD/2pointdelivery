@extends('admin.layouts.app')

@section('title', 'Clients')

@section('content')

    <section class="section p-0">
        <div class="container">
            <div class="section-header mb-2">
                <div class="d-flex justify-content-between">
                    <h4>Add Client</h4>
                </div>
            </div>
            <div class="section-body">
                <form action="{{ route('admin.client.store') }}" method="POST">
                    @csrf
                    @include('admin.clients.form')
                </form>

            </div>
        </div>
    </section>

@endsection
