<div class="sidebar">
    <a class="navbar-brand" href="{{ route('index') }}">
        <img src="{{ config('website_logo') ? asset('images/logo/' . config('website_logo')) : asset('images/logo/icon.png') }}"
            alt="2 Point" width="50">
        2 Point Client
    </a>
    <nav class="mt-3">
        <ul class="p-0">
            <li class="nav-item"><a class="nav-link" href="{{ route('client.index') }}"><i class="fa fa-home"></i>
                    Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('client.kyc_details') }}"><i class="fa fa-bank"></i>
                    KYC </a>
            </li>
            @if (app('userInfoHelper')->hasClientCompany())
                <li class="nav-item"><a class="nav-link" href="{{ route('client.team.index') }}"><i
                            class="fa fa-users"></i> Teams</a></li>
            @endif
            <li class="nav-item"><a class="nav-link" href="{{ route('client.bookings') }}"><i class="fa fa-dolly"></i>
                    Bookings</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('client.addressBooks') }}"><i
                        class="fa-solid fa-address-book"></i> Address Book</a></li>

            <li class="nav-item"><a class="nav-link" href="{{ route('client.invitations') }}"><i
                        class="fa-solid fa-handshake"></i> Invitations</a></li>

            <li class="nav-item"><a class="nav-link" href="{{ route('client.chats') }}"><i
                        class="fa-solid fa-inbox"></i>
                    Chat</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('client.referrals') }}"><i
                        class="fa-solid fa-repeat"></i> Referrals</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('client.trackOrder') }}"><i class="fa fa-map"></i>
                    Tracking</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('client.profile') }}"><i class="fa fa-edit"></i>
                    Edit Profile</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
                        class="fa-solid fa-arrow-right-from-bracket"></i> Logout</a>
            </li>
        </ul>

    </nav>
</div>
