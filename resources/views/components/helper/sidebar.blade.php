<div class="sidebar">
    <a class="navbar-brand" href="{{ route('index') }}">
        <img src="{{ config('website_logo') ? asset('images/logo/' . config('website_logo')) : asset('images/logo/icon.png') }}"
            alt="2 Point" width="50">
        2 Point Helper
    </a>
    <nav class="mt-3">
        <ul class="p-0">
            {{-- Dashboard --}}
            <li class="nav-item"><a class="nav-link" href="{{ route('helper.index') }}"><i class="fa fa-home"></i>
                    Dashboard</a></li>
            {{-- KYC --}}
            <li class="nav-item"><a class="nav-link" href="{{ route('helper.kyc_details') }}"><i class="fa fa-bank"></i>
                    KYC Detail</a>
            </li>
            {{-- Show Tams only if Helper is Company --}}
            @if (app('userInfoHelper')->hasHelperCompany())
                <li class="nav-item"><a class="nav-link" href="{{ route('helper.team.index') }}"><i
                            class="fa fa-users"></i> Teams</a></li>
            @endif
            {{-- Bookings --}}
            <li class="nav-item"><a class="nav-link" href="{{ route('helper.bookings') }}"><i class="fa fa-dolly"></i>
                    Bookings</a></li>
            {{-- Invitations --}}
            <li class="nav-item"><a class="nav-link" href="{{ route('helper.invitations') }}"><i
                        class="fa-solid fa-handshake"></i> Invitations</a></li>
            {{-- Chats --}}
            <li class="nav-item"><a class="nav-link" href="{{ route('helper.chats') }}"><i
                        class="fa-solid fa-inbox"></i>
                    Chat</a></li>
            {{-- Edit Profile --}}
            <li class="nav-item"><a class="nav-link" href="{{ route('helper.profile') }}"><i class="fa fa-edit"></i>
                    Edit Profile</a></li>
            {{-- Logout --}}
            <li class="nav-item"><a class="nav-link" href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
                        class="fa-solid fa-arrow-right-from-bracket"></i> Logout</a>
            </li>
        </ul>
        <a href="{{ route('client.index') }}" class="btn btn-primary">Switch to Client</a>
    </nav>
</div>


<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
