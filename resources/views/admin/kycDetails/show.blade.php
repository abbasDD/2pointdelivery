@extends('admin.layouts.app')

@section('title', 'KYC Details')

@section('content')

    <div class="container p-3 mb-5">

        <h4>KYC Details</h4>

        @include('admin.kycDetails.form')

    </div>



@endsection
