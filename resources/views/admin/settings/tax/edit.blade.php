@extends('admin.layouts.app')

@section('title', 'Tax Settings')

@section('content')

    <section class="section">
        <div class="container">
            <div class="section-header mb-2">
                <div class="d-flex justify-content-between">
                    <h4>Edit Tax</h4>
                </div>
            </div>
            <div class="section-body">
                <form action="{{ route('admin.taxSetting.update') }}" method="POST">
                    @csrf
                    @include('admin.settings.tax.form')
                </form>

            </div>
        </div>
    </section>

@endsection
