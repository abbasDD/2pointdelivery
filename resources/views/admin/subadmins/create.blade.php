@extends('admin.layouts.app')

@section('title', 'Sub Admins')

@section('content')

    <section class="section">
        <div class="container">
            <div class="section-header mb-2">
                <div class="d-flex justify-content-between">
                    <h4>Add Sub Admin</h4>
                </div>
            </div>
            <div class="section-body">
                <form action="{{ route('admin.subadmin.store') }}" method="POST">
                    @csrf
                    @include('admin.subadmins.form')
                </form>

            </div>
        </div>
    </section>

@endsection
