@extends('layouts.app')

@section('title', 'Admin Login')

@section('content')
    <div class="authpage">
        <div class="row align-content-center justify-content-center">
            <div class="col-md-6 d-grid align-items-center justify-content-center vh-100">
                <div class="card" style="box-shadow: 0 0 10px 0 rgba(0, 0, 0, 0.2);">
                    <div class="card-body text-center">
                        <a href="{{ route('index') }}">
                            <img src="{{ config('website_logo') ? asset('images/logo/' . config('website_logo')) : asset('images/logo/icon.png') }}"
                                alt="2 Point" height="50">
                        </a>
                        <h3>2 Point Admin</h3>
                        <p>Please enter your detail to login</p>
                        <form method="POST" action="{{ route('admin.login') }}">
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
