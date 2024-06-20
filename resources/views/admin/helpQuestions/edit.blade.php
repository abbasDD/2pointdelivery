@extends('admin.layouts.app')

@section('title', 'Help Questions')

@section('content')

    <section class="section p-0">
        <div class="container">
            <div class="section-header mb-2">
                <div class="d-flex justify-content-between">
                    <h4>Edit Help Question</h4>
                </div>
            </div>
            <div class="section-body">

                @trixassets

                <form action="{{ route('admin.helpQuestion.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $helpQuestion->id }}">
                    @include('admin.helpQuestions.form')
                </form>

            </div>
        </div>
    </section>

@endsection
