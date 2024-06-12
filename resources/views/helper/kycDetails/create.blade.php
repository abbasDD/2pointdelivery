@extends('helper.layouts.app')

@section('title', 'KYC ')

@section('content')

    <section class="section p-0">
        <div class="container">
            <div class="section-header mb-2">
                <div class="d-flex justify-content-between">
                    <h4>Add KYC</h4>
                </div>
            </div>
            <div class="section-body">
                <form action="{{ route('helper.kyc.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @include('helper.kycDetails.form')
                </form>

            </div>
        </div>
    </section>

@endsection
