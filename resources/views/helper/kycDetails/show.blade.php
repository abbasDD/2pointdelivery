@extends('helper.layouts.app')

@section('title', 'KYC Details')

@section('content')

    <div class="container p-3 mb-5">

        <h4>KYC Details</h4>

        <form id="kycForm" action="{{ route('helper.kyc.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" value="{{ $kycDetails->id }}">
            @include('helper.kycDetails.form')
        </form>

    </div>



@endsection
