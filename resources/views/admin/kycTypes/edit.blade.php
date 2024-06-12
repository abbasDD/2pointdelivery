@extends('admin.layouts.app')

@section('title', 'Kyc Type')

@section('content')

    <section class="section p-0">
        <div class="container">
            <div class="section-header mb-2">
                <div class="d-flex justify-content-between">
                    <h4>Edit Kyc Type</h4>
                </div>
            </div>
            <div class="section-body">
                <form action="{{ route('admin.kycType.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $kycType->id }}">
                    @include('admin.kycTypes.form')
                </form>

            </div>
        </div>
    </section>

@endsection
