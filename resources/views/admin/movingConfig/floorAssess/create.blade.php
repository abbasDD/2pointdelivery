@extends('admin.layouts.app')

@section('title', 'Moving Config')

@section('content')

    <section class="section p-0">
        <div class="container">
            <div class="section-header mb-2">
                <div class="d-flex justify-content-between">
                    <h4>Add Floor Access</h4>
                </div>
            </div>
            <div class="section-body">
                <form action="{{ route('admin.movingConfig.floorAssess.store') }}" method="POST">
                    @csrf
                    @include('admin.movingConfig.floorAssess.form')
                </form>

            </div>
        </div>
    </section>

@endsection
