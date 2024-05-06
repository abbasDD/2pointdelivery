@extends('admin.layouts.app')

@section('title', 'FAQ')

@section('content')

    <section class="section">
        <div class="container">
            <div class="section-header mb-2">
                <div class="d-flex justify-content-between">
                    <h4>Edit FAQ</h4>
                </div>
            </div>
            <div class="section-body">
                <form action="{{ route('admin.faq.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $faq->id }}">
                    @include('admin.faqs.form')
                </form>

            </div>
        </div>
    </section>

@endsection
