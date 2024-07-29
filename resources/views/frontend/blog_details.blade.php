@extends('frontend.layouts.app')

@section('title', 'Blogs')

@section('content')

    {{-- Header Section  --}}
    <section class="py-3 bg-light-gray">
        <div class="container my-5">
            <div class="row align-items-center">
                <div class="col-md-12 px-5 text-center">
                    <h2>{{ $blog->title }}</h2>
                    <p>By: {{ $blog->author }}</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Blog Details --}}
    <section class="my-5">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    {!! $blog->body !!}
                </div>
            </div>
        </div>
    </section>



@endsection
