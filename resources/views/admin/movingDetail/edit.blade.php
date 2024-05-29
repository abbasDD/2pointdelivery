@extends('admin.layouts.app')

@section('title', 'Moving Config')

@section('content')

    <section class="section">
        <div class="container">
            <div class="section-header mb-2">
                <div class="d-flex justify-content-between">
                    <h4>Edit Floor Plan</h4>
                </div>
            </div>
            <div class="section-body">
                <form action="{{ route('admin.movingDetail.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $movingDetail->id }}">
                    @include('admin.movingDetail.form')
                </form>

            </div>
        </div>
    </section>

@endsection
