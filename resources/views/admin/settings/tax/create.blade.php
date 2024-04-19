@extends('admin.layouts.app')

@section('title', 'New Tax Country')

@section('content')

    <section class="section">
        <div class="container">
            <div class="section-header mb-2">
                <div class="d-flex justify-content-between">
                    <h4>Add Tax</h4>
                </div>
            </div>
            <div class="section-body">
                <form action="{{ route('admin.taxSetting.store') }}" method="POST">
                    @csrf
                    @include('admin.settings.tax.form')
                </form>

            </div>
        </div>
    </section>

@endsection
