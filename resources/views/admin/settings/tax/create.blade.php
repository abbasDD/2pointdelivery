@extends('admin.layouts.app')

@section('title', 'New Tax Country')

@section('content')

    <section class="section p-0">
        <div class="container">
            <div class="section-header mb-2">
                <div class="d-flex justify-content-between">
                    <h4>Add Country Tax</h4>
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
