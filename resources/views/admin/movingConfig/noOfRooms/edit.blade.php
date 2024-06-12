@extends('admin.layouts.app')

@section('title', 'Moving Config')

@section('content')

    <section class="section p-0">
        <div class="container">
            <div class="section-header mb-2">
                <div class="d-flex justify-content-between">
                    <h4>Edit No of Rooms</h4>
                </div>
            </div>
            <div class="section-body">
                <form action="{{ route('admin.movingConfig.noOfRooms.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $noOfRoom->id }}">
                    @include('admin.movingConfig.noOfRooms.form')
                </form>

            </div>
        </div>
    </section>

@endsection
