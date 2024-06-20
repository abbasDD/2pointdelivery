@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')

    <div class="authpage">
        <div class="wrapper" style="background-image: url('{{ asset('frontend/images/client-bg.png') }}');">
            <div class="inner">
                <div class="image-holder text-center">
                    <img class="img-fluid mb-3" src="{{ asset('frontend/images/client.png') }}" alt="2 Point">
                </div>
                {{-- Forget Password Form --}}
                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="row">

                        <div class="col-md-12">
                            <div class="heading">
                                <h6>
                                    <a class="text-primary" href="{{ route('index') }}">
                                        2 Point Delivery
                                    </a>
                                </h6>
                                <h2 class="mb-1">{{ __('auth.forgot_password') }}</h2>
                                <p>{{ __('auth.login.subtitle') }}</p>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                name="email" value="{{ old('email') }}" placeholder="Email" required autocomplete="email"
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


                    <div class="mb-3 text-center">

                        <p>
                            Alrady have an account?
                            <a class="" href="{{ route('client.login') }}">
                                Login
                            </a>
                        </p>
                    </div>
                </form>

            </div>
        </div>
    </div>


@endsection
