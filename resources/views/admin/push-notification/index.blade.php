@extends('admin.layouts.app')

@section('title', 'Push Notification')

@section('content')

    <section class="section p-0">
        <div class="container">
            <div class="section-header mb-2">
                <div class="d-flex justify-content-between">
                    <h4 class="mb-0">Push Notification</h4>
                </div>
            </div>
            <div class="section-body">
                <div id="adminPushNotificationTable">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Title</th>
                                <th scope="col">Body</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($notifications as $notification)
                                <tr>
                                    <th scope="row">{{ $notification->id }}</th>
                                    <td>{{ $notification->title }}</td>
                                    <td>{!! $notification->body !!}</td>
                                    <td>
                                        <a href="{{ route('admin.pushNotification.resend', $notification->id) }}"
                                            class="btn btn-sm btn-primary">Send</a>
                                        <i class="fa fa-send"></i>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No notifications found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

@endSection
