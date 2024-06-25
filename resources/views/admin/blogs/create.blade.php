@extends('admin.layouts.app')

@section('title', 'Blogs')

@section('content')

    <section class="section p-0">
        <div class="container">
            <div class="section-header mb-2">
                <div class="d-flex justify-content-between">
                    <h4>Add Blog</h4>
                </div>
            </div>
            <div class="section-body">

                @trixassets

                <form action="{{ route('admin.blog.store') }}" method="POST">
                    @csrf
                    @include('admin.blogs.form')
                </form>

            </div>
        </div>
    </section>

@endsection
