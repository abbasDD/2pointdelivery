<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top py-3">
    <div class="container">
        <a class="navbar-brand" href="{{ route('index') }}">
            <img src="{{ config('website_logo') ? asset('images/logo/' . config('website_logo')) : asset('images/logo/icon.png') }}"
                alt="2 Point" width="50">
            <span class="text-logo ml-2">{{ config('website_name') ?: 'Website Name' }} </span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo03"
            aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('/') || request()->is('index') ? 'active' : '' }}"
                        href="{{ route('index') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('services') ? 'active' : '' }}"
                        href="{{ route('services') }}">Services</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('about-us') ? 'active' : '' }}"
                        href="{{ route('about-us') }}">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('help') ? 'active' : '' }}" href="{{ route('help') }}">Help</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('join-helper') ? 'active' : '' }}"
                        href="{{ route('join_helper') }}">Join as Helper</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        {{ app()->getLocale() }}
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <li><a class="dropdown-item"
                                href="{{ route('change-language', ['lang' => 'en']) }}">English</a></li>
                        <li><a class="dropdown-item" href="{{ route('change-language', ['lang' => 'fr']) }}">French</a>
                        </li>
                        <!-- Add other languages here -->
                    </ul>
                </li>
            </ul>

            <div class="d-flex">
                @auth
                    <div class="dropdown">
                        <p class="nav-link dropdown-toggle mb-0" type="button" id="dropdownMenuButton"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <img class="user-image d-inline rounded-circle" src="{{ asset('images/default-user.jpg') }}"
                                width="35" height="35" alt="User">
                        </p>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            @if (auth()->user()->user_type == 'admin')
                                <li><a class="dropdown-item" href="{{ route('admin.index') }}">Admin</a></li>
                            @else
                                <li><a class="dropdown-item" href="{{ route('client.index') }}">Client</a></li>
                                <li><a class="dropdown-item" href="{{ route('helper.index') }}">Helper</a></li>
                            @endif
                            <li><a class="dropdown-item" href="{{ route('chat') }}">Chat</a></li>
                            <li><a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                            </li>
                        </ul>
                    </div>
                @else
                    <!-- If user is not logged in, show sign in button -->
                    {{-- <a href="{{ route('client.login') }}" class="nav-link"><i class="fa-regular fa-user mx-2"></i>Sign
                        In</a> --}}
                    {{-- Redirect to Helper Register --}}
                    <div class="arrow-button">
                        <a href="{{ route('client.login') }}">
                            <i class="fas fa-long-arrow-alt-right mr-2"></i> Sign In
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</nav>
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
