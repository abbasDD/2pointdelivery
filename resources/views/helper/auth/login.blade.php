@extends('layouts.app')

@section('title', 'Helper Login')

@section('content')
    <div class="authpage">
        <div class="row align-content-center m-0">
            <div class="bg-gradient col-md-6 d-none d-md-block align-content-center">
                <div class="d-flex align-items-center justify-content-center">
                    <img src="{{ asset('images/auth/helper-bg.png') }}" width="400" alt="auth image">
                </div>
            </div>
            <div class="col-md-6 d-grid align-items-center justify-content-center">
                <div class="card">
                    <div class="w-100 mb-3 switch-user">
                        <a class="" href="{{ route('client.login') }}">
                            Login as Client
                        </a>
                    </div>
                    <div class="card-body text-center">
                        <a href="{{ route('index') }}">
                            <img src="{{ config('website_logo') ? asset('images/logo/' . config('website_logo')) : asset('images/logo/icon.png') }}"
                                alt="2 Point" height="50">
                        </a>
                        <h3>2 Point Helper</h3>
                        <p>Please enter your detail to login</p>
                        <form method="POST" action="{{ route('helper.login') }}">
                            @csrf
                            {{-- Hidden field for helper --}}
                            <input type="hidden" name="user_type" value="helper">

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


                        {{-- Seprator --}}
                        <div class="my-5">
                            <hr>
                        </div>

                        {{-- Google Login Button  --}}
                        <div class="mb-3">
                            <a href="{{ route('google.redirect') }}" class="btn btn-google w-100">
                                Continue with Google | <i class="fa-brands fa-google"></i>
                            </a>
                        </div>

                        {{-- Facebook Login Button  --}}
                        <div class="mb-3">
                            <a href="#" class="btn btn-facebook w-100">
                                Continue with Google | <i class="fa-brands fa-facebook"></i>
                            </a>
                        </div>

                        <div class="mb-3">
                            <p>
                                Dont have an account?
                                <a class="" href="{{ route('helper.register') }}">
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
