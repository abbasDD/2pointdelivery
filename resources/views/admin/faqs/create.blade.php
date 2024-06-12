@extends('admin.layouts.app')

@section('title', 'FAQs')

@section('content')

    <section class="section p-0">
        <div class="container">
            <div class="section-header mb-2">
                <div class="d-flex justify-content-between">
                    <h4>Add FAQ</h4>
                </div>
            </div>
            <div class="section-body">
                <form action="{{ route('admin.faq.store') }}" method="POST">
                    @csrf
                    @include('admin.faqs.form')
                </form>

            </div>
        </div>
    </section>

@endsection
