@extends('admin.layouts.app')

@section('title', 'KYC Types')

@section('content')

    <section class="section">
        <div class="container">
            <div class="section-header mb-2">
                <div class="d-flex justify-content-between">
                    <h4>Add KYC Type</h4>
                </div>
            </div>
            <div class="section-body">
                <form action="{{ route('admin.kycType.store') }}" method="POST">
                    @csrf
                    @include('admin.kycTypes.form')
                </form>

            </div>
        </div>
    </section>

@endsection
