@extends('admin.layouts.app')

@section('title', 'Priority Settings')

@section('content')

    <section class="section">
        <div class="container">
            <div class="section-header mb-2">
                <div class="d-flex justify-content-between">
                    <h4>Edit Tax</h4>
                </div>
            </div>
            <div class="section-body">
                <form action="{{ route('admin.deliveryConfig.priority.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $prioritySetting->id }}">
                    @include('admin.deliveryConfig.priority.form')
                </form>

            </div>
        </div>
    </section>

@endsection
