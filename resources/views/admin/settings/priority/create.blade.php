@extends('admin.layouts.app')

@section('title', 'Priority Settings')

@section('content')

    <section class="section">
        <div class="container">
            <div class="section-header mb-2">
                <div class="d-flex justify-content-between">
                    <h4>Add Priority</h4>
                </div>
            </div>
            <div class="section-body">
                <form action="{{ route('admin.prioritySetting.store') }}" method="POST">
                    @csrf
                    @include('admin.settings.priority.form')
                </form>

            </div>
        </div>
    </section>

@endsection
