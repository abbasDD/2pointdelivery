@extends('admin.layouts.app')

@section('title', 'Moving Configuration')

@section('content')

    <section class="section">
        <div class="container">
            <div class="section-header mb-2">
                <div class="d-flex justify-content-between">
                    <h4>Moving Configuration</h4>
                </div>
            </div>
            <div class="section-body">
                <form action="{{ route('admin.movingConfig.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $movingConfig->id }}">
                    @include('admin.movingConfig.form')
                </form>

            </div>
        </div>
    </section>

@endsection
