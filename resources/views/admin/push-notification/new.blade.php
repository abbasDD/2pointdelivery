@extends('admin.layouts.app')

@section('title', 'Push Notification')

@section('content')

    <section class="section p-0">
        <div class="container">
            <div class="section-header mb-2">
                <div class="d-flex justify-content-between">
                    <h4 class="mb-0">Send Push Notification</h4>
                </div>
            </div>
            <div class="section-body">
                <form action="{{ route('admin.pushNotification.send') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @include('admin.push-notification.form')
                </form>
            </div>
        </div>
    </section>

@endSection
