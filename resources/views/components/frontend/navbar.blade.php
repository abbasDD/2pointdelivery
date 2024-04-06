<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
    <div class="container">
        <a class="navbar-brand" href="{{ route('index') }}">
            <img src="{{ asset('images/logo/logo.png') }}" alt="2 Point" height="30">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo03"
            aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="{{ route('index') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('services') }}">Services</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('about-us') }}">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('help') }}">Help</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('join_helper') }}">Join as Helper</a>
                </li>
            </ul>

            <div class="d-flex">
                @auth
                    <div class="dropdown">
                        <p class="btn btn-link nav-link dropdown-toggle mb-0" type="button" id="dropdownMenuButton"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <img class="user-image d-inline rounded-circle" src="{{ asset('images/default-user.jpg') }}"
                                width="35" height="35" alt="User">
                        </p>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            @if (auth()->user()->user_type == 'helper')
                                <li><a class="dropdown-item" href="{{ route('helper.index') }}">Dashboard</a></li>
                            @else
                                <li><a class="dropdown-item" href="{{ route('client.index') }}">Dashboard</a></li>
                            @endif
                            <li><a class="dropdown-item" href="#">Messages</a></li>
                            <li><a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                            </li>
                        </ul>
                    </div>
                @else
                    <!-- If user is not logged in, show sign in button -->
                    <a href="{{ route('client.login') }}" class="nav-link"><i class="fa-regular fa-user mx-2"></i>Sign
                        In</a>
                @endauth
            </div>
        </div>
    </div>
</nav>
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
