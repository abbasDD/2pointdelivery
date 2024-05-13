@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
    <div class="authpage">
        <div class="row align-content-center m-0">
            <div class="bg-gradient col-md-6 d-none d-md-block align-content-center">
                <div class="d-flex align-items-center justify-content-center">
                    <img src="{{ asset('images/auth/client-bg.png') }}" width="400" alt="auth image">
                </div>
            </div>
            <div class="col-md-6 d-grid align-items-center justify-content-center">
                <div class="card">

                    <div class="card-body text-center">
                        <img src="{{ config('website_logo') ? asset('images/logo/' . config('website_logo')) : asset('images/logo/icon.png') }}"
                            alt="2 Point" height="50">
                        <h3>Reset Password</h3>
                        <p>Please enter your email to reset password</p>
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf

                            <div class="row">
                                <div class="col-md-12">
                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ old('email') }}" placeholder="Email" required autocomplete="email"
                                        autofocus>

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-5">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary w-100">
                                        {{ __('Send Password Reset Link') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                        <div class="mb-3">

                            <p>
                                Alrady have an account?
                                <a class="" href="{{ route('client.login') }}">
                                    Login
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
