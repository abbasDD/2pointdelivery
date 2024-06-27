@extends('layouts.app')

@section('title', 'Client Register')

@section('content')
    <div class="authpage">
        <div class="wrapper" style="background-image: url('{{ asset('frontend/images/client-bg.png') }}');">
            <div class="inner">
                <div class="image-holder text-center">
                    <img class="img-fluid mb-3" src="{{ asset('frontend/images/client.png') }}" alt="2 Point">
                    {{-- Switch to Helper --}}
                    <div class="arrow-button">
                        <a class="text-primary" href="{{ route('helper.login') }}">
                            <i class="fas fa-long-arrow-alt-right mr-2"></i>
                            {{ __('auth.switch_to_helper') }}
                        </a>
                    </div>
                </div>
                <form method="POST" action="{{ route('client.register') }}">
                    @csrf
                    {{-- Hidden field for client --}}
                    <input type="hidden" name="user_type" value="user">

                    <div class="row">

                        <div class="col-md-12">
                            <div class="heading">
                                <h6>
                                    <a class="text-primary" href="{{ route('index') }}">
                                        2 Point Delivery
                                    </a>
                                </h6>
                                <h2 class="mb-1">{{ __('auth.register') }}</h2>
                                <p>{{ __('Please enter your details to create account') }}</p>
                            </div>
                        </div>


                        <div class="col-md-12">
                            <select id="account_type" class="form-control @error('name') is-invalid @enderror"
                                name="account_type" required autocomplete="account_type" autofocus>
                                <option value="" selected disabled>Choose Account Type</option>
                                <option value="individual">Individual</option>
                                <option value="company">Company</option>
                            </select>

                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-12">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                name="email" value="{{ old('email') }}" placeholder="{{ __('auth.email') }}" required
                                autocomplete="email">

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 position-relative">
                            <input id="password" type="password"
                                class="form-control @error('password') is-invalid @enderror" name="password"
                                placeholder="{{ __('auth.password') }}" required autocomplete="new-password">
                            <span class="toggle-password" onclick="togglePasswordVisibility()">
                                <i class="fas fa-eye"></i>
                            </span>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 position-relative">
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation"
                                placeholder="{{ __('auth.confirm_password') }}" required autocomplete="new-password">
                            <span class="toggle-confirm-password" onclick="toggleConfirmPasswordVisibility()">
                                <i class="fas fa-eye"></i>
                            </span>
                        </div>
                    </div>

                    {{-- referral code --}}
                    <div class="row">
                        <div class="col-md-12">
                            <input id="referral_code" type="text"
                                class="form-control @error('referral_code') is-invalid @enderror" name="referral_code"
                                placeholder="{{ __('auth.refferal_code') }}" value="{{ $referralCode ?? '' }}">
                            @error('referral_code')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    {{-- Accept Terms and Conditions --}}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="terms_and_conditions"required>

                                <label class="form-check-label fs-xxs" for="terms_and_conditions">
                                    Please accept our <a class="fs-xxs text-primary"
                                        href="{{ route('terms_and_conditions') }}" target="_blank">Terms and
                                        Conditions</a>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary w-100">
                                {{ __('auth.register') }}
                            </button>
                        </div>
                    </div>


                    <div class="mb-3 text-center">

                        <p>
                            {{ __('auth.already_account') }}
                            <a class="" href="{{ route('client.login') }}">
                                {{ __('auth.login.heading') }}
                            </a>
                        </p>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        function togglePasswordVisibility() {
            var passwordInput = document.getElementById("password");
            var icon = document.querySelector(".toggle-password i");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                passwordInput.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }

        function toggleConfirmPasswordVisibility() {
            var passwordInput = document.getElementById("password-confirm");
            var icon = document.querySelector(".toggle-confirm-password i");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                passwordInput.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }
    </script>
@endsection
