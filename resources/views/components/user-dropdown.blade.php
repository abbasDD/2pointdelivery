<div class="dropdown d-none d-md-block">
    <p class="nav-link dropdown-toggle mb-0" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown"
        aria-expanded="false">
        <img class="user-image d-inline rounded-circle"
            src="{{ session('profile_image') ?? asset('images/default-user.jpg') }}" width="35" height="35"
            alt="User"> {{ session('full_name') ?? 'Your Name' }}
    </p>
    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        <li>
            <div class="mb-3 text-center">
                <h6 class="mb-0 fs-16">{{ session('full_name') ?? 'Your Name' }}</h6>
                @if (session('login_type') != 'admin')
                    <p class="mb-0 fs-12 text-muted">Member ID {{ auth()->user()->referral_code ?? 'Code' }}
                    </p>
                @endif
            </div>
        </li>
        @if (session('login_type') == 'admin')
            <li><a class="dropdown-item" href="{{ route('admin.index') }}"><i class="fa fa-home"></i> Dashboard</a></li>
        @else
            @if (session('login_type') == 'helper')
                <li><a class="dropdown-item" href="{{ route('helper.index') }}"><i class="fa fa-home"></i> Dashboard</a>
                </li>
                <li><a class="dropdown-item" href="{{ route('helper.profile') }}"><i class="fa fa-user"></i> Profile</a>
                </li>
                <li><a class="dropdown-item" href="#"><i class="fa fa-cog"></i> Settings</a></li>
            @else
                <li><a class="dropdown-item" href="{{ route('client.index') }}"><i class="fa fa-home"></i> Dashboard</a>
                </li>
                <li><a class="dropdown-item" href="{{ route('client.profile') }}"><i class="fa fa-user"></i> Profile</a>
                </li>
                <li><a class="dropdown-item" href="#"><i class="fa fa-cog"></i> Settings</a></li>
            @endif
        @endif
        <li><a class="dropdown-item" href="{{ route('help') }}"> <i class="fa fa-question-circle"></i> Help</a></li>
        <li><a class="dropdown-item" href="{{ route('logout') }}"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
                    class="fa-solid fa-arrow-right-from-bracket"></i> Logout</a>
        </li>
        @if (session('login_type') != 'admin')
            <hr>
            <li>
                @if (session('login_type') == 'helper')
                    <a href="{{ route('helper.switchToClient') }}" class="dropdown-item"><i class="fa fa-bicycle"></i>
                        Switch to Client
                    </a>
                @else
                    <a href="{{ route('client.switchToHelper') }}" class="dropdown-item"><i class="fa fa-bicycle"></i>
                        Switch to Helper
                    </a>
                @endif
            </li>
        @endif
    </ul>
</div>
