@extends('layouts.app')

@section('title', 'Helper Login')

@section('content')
    <div class="authpage">
        <div class="wrapper" style="background-image: url('{{ asset('frontend/images/helper-bg.png') }}');">
            <div class="inner">
                <div class="image-holder text-center">
                    <img class="img-fluid mb-3" src="{{ asset('frontend/images/helper.png') }}" alt="2 Point">
                    {{-- Switch to Helper --}}
                    <div class="arrow-button">
                        <a class="text-primary" href="{{ route('client.login') }}">
                            <i class="fas fa-long-arrow-alt-right mr-2"></i>
                            {{ __('auth.switch_to_client') }}
                        </a>
                    </div>
                </div>
                <form method="POST" action="{{ route('helper.login') }}">
                    @csrf
                    {{-- Hidden field for helper --}}
                    <input type="hidden" name="user_type" value="helper">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="heading">
                                <h6>
                                    <a class="text-primary" href="{{ route('index') }}">
                                        2 Point Delivery
                                    </a>
                                </h6>
                                <h2 class="mb-1">{{ __('auth.login.title') }}</h2>
                                <p>{{ __('auth.login.subtitle') }}</p>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                name="email" value="{{ old('email') }}" placeholder="{{ __('auth.email') }}" required
                                autocomplete="email" autofocus>

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
                                placeholder="{{ __('auth.password') }}" required autocomplete="current-password">
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
                                        {{ __('auth.login.forgot_password') }}
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
                                {{ __('auth.login.remember_me') }}
                            </label>
                        </div>
                    </div>
                </div> --}}

                    <div class="mb-5">
                        <button type="submit" class="btn btn-primary w-100">
                            {{ __('auth.login.heading') }}
                        </button>

                    </div>

                    <div class="d-flex justify-content-center gap-3">

                        {{-- Google Login Button  --}}
                        @if (isset($socialLoginSettingPair['google_enabled']) && $socialLoginSettingPair['google_enabled'] == 'yes')
                            <div class="mb-3">
                                <a href="{{ url('auth/google') }}" class="btn btn-google"><i
                                        class="fa-brands fa-google"></i>
                                </a>
                            </div>
                        @endif

                        {{-- Facebook Login Button  --}}
                        @if (isset($socialLoginSettingPair['facebook_enabled']) && $socialLoginSettingPair['facebook_enabled'] == 'yes')
                            <div class="mb-3">
                                <a href="#" class="btn btn-facebook"><i class="fa-brands fa-facebook"></i>
                                </a>
                            </div>
                        @endif
                    </div>

                    <div class="mb-3 text-center">
                        <p>
                            {{ __('auth.login.no_account') }}
                            <a class="" href="{{ route('helper.register') }}">
                                Register
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
    </script>
@endsection
