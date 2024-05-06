@extends('layouts.app')

@section('title', 'Client Login')

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
                        <img src="{{ asset('images/logo/' . config('website_logo')) ?: asset('images/logo/icon.png') }}"
                            alt="2 Point" height="50">
                        <h3>2 Point Client</h3>
                        <p>Please enter your detail to login</p>
                        <form method="POST" action="{{ route('client.login') }}">
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

                            <div class="row">
                                <div class="col-md-12 position-relative">
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        placeholder="Password" required autocomplete="current-password">
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
                                <div class="col-12 forgot-password">
                                    @if (Route::has('password.request'))
                                        <p>
                                            <a href="{{ route('password.request') }}">
                                                {{ __('Forgot Your Password?') }}
                                            </a>
                                        </p>
                                    @endif
                                </div>
                            </div>

                            {{-- <div class="row">
                            <div class="col-md-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div> --}}

                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary w-100">
                                    {{ __('Login') }}
                                </button>

                            </div>
                        </form>
                        <div class="mb-3">

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
    </script>
@endsection
