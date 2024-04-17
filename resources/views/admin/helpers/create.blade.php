@extends('admin.layouts.app')

@section('title', 'Helpers')

@section('content')

    <section class="section">
        <div class="container">
            <div class="section-header mb-2">
                <div class="d-flex justify-content-between">
                    <h4>Add Helper</h4>
                </div>
            </div>
            <div class="section-body">
                <form action="{{ route('admin.helper.store') }}" method="POST">
                    @csrf
                    @include('admin.helpers.form')
                </form>

            </div>
        </div>
    </section>

@endsection
