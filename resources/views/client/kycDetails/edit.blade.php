@extends('client.layouts.app')

@section('title', 'KYC ')

@section('content')

    <section class="section p-0">
        <div class="container">
            <div class="section-header mb-2">
                <div class="d-flex justify-content-between">
                    <h4>Edit KYC</h4>
                </div>
            </div>
            <div class="section-body">
                {{-- Show Error --}}
                <div class="text-danger">{{ $errors->first() }}</div>

                <form action="{{ route('client.kyc.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    {{-- Hidden filed for id of kycDetails --}}
                    <input type="hidden" name="id" value="{{ $kycDetails->id }}">
                    @include('client.kycDetails.form')
                </form>

            </div>
        </div>
    </section>

@endsection
