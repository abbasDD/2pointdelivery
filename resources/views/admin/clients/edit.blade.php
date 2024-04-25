@extends('admin.layouts.app')

@section('title', 'Client ')

@section('content')

    <section class="section">
        <div class="container">
            <div class="section-header mb-2">
                <div class="d-flex justify-content-between">
                    <h4>Edit Client</h4>
                </div>
            </div>
            <div class="section-body">
                <form action="{{ route('admin.client.update') }}" method="POST">
                    @csrf
                    @include('admin.clients.form')
                </form>

            </div>
        </div>
    </section>

@endsection
