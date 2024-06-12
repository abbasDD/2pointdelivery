@extends('admin.layouts.app')

@section('title', 'Tax Settings')

@section('content')

    <section class="section p-0">
        <div class="container">
            <div class="section-header mb-2">
                <div class="d-flex justify-content-between">
                    <h4>Edit Country Tax</h4>
                </div>
            </div>
            <div class="section-body">
                <form action="{{ route('admin.taxSetting.update') }}" method="POST">
                    @csrf
                    {{-- Add a hidden id field --}}
                    <input type="hidden" name="id" value="{{ $taxCountry->id }}">
                    @include('admin.settings.tax.form')
                </form>

            </div>
        </div>
    </section>

@endsection
