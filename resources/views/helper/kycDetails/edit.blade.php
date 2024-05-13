@extends('helper.layouts.app')

@section('title', 'KYC ')

@section('content')

    <section class="section">
        <div class="container">
            <div class="section-header mb-2">
                <div class="d-flex justify-content-between">
                    <h4>Edit KYC</h4>
                </div>
            </div>
            <div class="section-body">
                <form action="{{ route('helper.kyc.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    {{-- Hidden filed for id of kycDetails --}}
                    <input type="hidden" name="id" value="{{ $kycDetails->id }}">
                    @include('helper.kycDetails.form')
                </form>

            </div>
        </div>
    </section>

@endsection
