@extends('layouts.app')

@section('title', 'Login')

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

                    <div class="card-body ">
                        <div class="text-center">
                            <a href=" {{ route('index') }}">
                                <img src="{{ config('website_logo') ? asset('images/logo/' . config('website_logo')) : asset('images/logo/icon.png') }}"
                                    alt="2 Point" height="50">
                            </a>
                            <h3>Reset Password</h3>
                            <p>Please enter your new password</p>
                        </div>
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf

                            <input type="hidden" name="token" value="{{ $token }}">

                            <div class="row">
                                <label for="email" class="col-md-12 col-form-label">{{ __('Email Address') }}</label>

                                <div class="col-md-12">
                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus
                                        readonly>

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <label for="password" class="col-md-12 col-form-label">{{ __('Password') }}</label>

                                <div class="col-md-12">
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        required autocomplete="new-password">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <label for="password-confirm"
                                    class="col-md-12 col-form-label">{{ __('Confirm Password') }}</label>

                                <div class="col-md-12">
                                    <input id="password-confirm" type="password" class="form-control"
                                        name="password_confirmation" required autocomplete="new-password">
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-12 text-center mb-3">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Reset Password') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                        <div class="mb-3 text-center">

                            <p>
                                Dont have an account?
                                <a class="" href="{{ route('client.register') }}">
                                    Register
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
