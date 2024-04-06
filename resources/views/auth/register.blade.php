@extends('layouts.app')

@section('title', 'Client Register')

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

                    <div class="card-body text-center">
                        <img src="{{ asset('images/logo/icon.png') }}" alt="logo">
                        <h3>2 Point Client</h3>
                        <p>Please enter your detail to login</p>
                        <form method="POST" action="{{ route('client.register') }}">
                            @csrf

                            <div class="row mb-3">
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


                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ old('email') }}" placeholder="Email Address" required
                                        autocomplete="email">

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12 position-relative">
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        placeholder="Password" required autocomplete="new-password">
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

                            <div class="row mb-3">
                                <div class="col-md-12 position-relative">
                                    <input id="password-confirm" type="password" class="form-control"
                                        name="password_confirmation" placeholder="Confirm Password" required
                                        autocomplete="new-password">
                                    <span class="toggle-confirm-password" onclick="toggleConfirmPasswordVisibility()">
                                        <i class="fas fa-eye"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary w-100">
                                        {{ __('Register') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                        <div class="mb-3">

                            <p>
                                Already have an account?
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
