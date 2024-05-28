@extends('admin.layouts.app')

@section('title', 'Moving Config')

@section('content')

    <section class="section">
        <div class="container">
            <div class="section-header mb-2">
                <div class="d-flex justify-content-between">
                    <h4>Edit Floor Access</h4>
                </div>
            </div>
            <div class="section-body">
                <form action="{{ route('admin.movingConfig.floorAssess.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $floorAssess->id }}">
                    @include('admin.movingConfig.floorAssess.form')
                </form>

            </div>
        </div>
    </section>

@endsection
