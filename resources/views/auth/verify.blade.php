@extends('layouts.app')

@section('title', 'Verify Password')

@section('content')
<div class="authpage">
    <div class="row align-content-center vh-100">
        <div class="col-md-6 d-none d-md-block">
            <div class="bg-gradient vh-100 d-flex align-items-center justify-content-center">
                <img src="{{ asset('images/auth/image-1.png') }}" alt="auth image">
            </div>
        </div>
        <div class="col-md-6 d-grid align-items-center justify-content-center">
            <div class="card">

                <div class="card-body">
                    <img src="{{ asset('images/logo/icon.png') }}" alt="logo">
                    <h3>Verify Email</h3>
                    <p>Please enter your code</p>
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('A fresh verification link has been sent to your email address.') }}
                        </div>
                    @endif

                    {{ __('Before proceeding, please check your email for a verification link.') }}
                    {{ __('If you did not receive the email') }},
                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('click here to request another') }}</button>.
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
