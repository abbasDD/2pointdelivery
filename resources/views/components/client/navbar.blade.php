<nav class="navbar navbar-left navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">

        <div class="d-flex align-items-center ">
            <button id="sidebarToggle" class="btn btn-sm btn-outline mx-3">
                <i class="fas fa-bars"></i>
            </button>

            <h5 class="m-0 d-none d-md-block">Client Dashboard</h5>
        </div>

        <div class="d-flex">
            @auth
                <div class="dropdown">
                    <p class="nav-link dropdown-toggle mb-0" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown"
                        aria-expanded="false">
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
                <a href="{{ route('client.login') }}" class="nav-link"><i class="fa-regular fa-user mx-2"></i>Sign
                    In</a>
            @endauth
        </div>
    </div>
</nav>

{{-- Sidebar Code her --}}
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
            <li class="nav-item"><a class="nav-link" href="{{ route('client.kyc_details') }}"><i
                        class="fa fa-bank"></i>
                    KYC </a>
            </li>
            @if (Auth::user()->account_type == 'company')
                <li class="nav-item"><a class="nav-link" href="#"><i class="fa fa-users"></i> Teams</a></li>
            @endif
            <li class="nav-item"><a class="nav-link" href="{{ route('client.bookings') }}"><i class="fa fa-dolly"></i>
                    Bookings</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('client.addressBooks') }}"><i
                        class="fa-solid fa-address-book"></i> Address Book</a></li>
            {{-- <li class="nav-item"><a class="nav-link" href="{{ route('client.invoices') }}"><i
                        class="fa fa-file-invoice"></i>
                    Invoices</a></li> --}}
            <li class="nav-item"><a class="nav-link" href="{{ route('client.chats') }}"><i class="fa fa-comment"></i>
                    Chat</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('client.referrals') }}"><i
                        class="fa-solid fa-repeat"></i> Referrals</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('client.trackOrder') }}"><i class="fa fa-map"></i>
                    Track Booking</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('client.profile') }}"><i class="fa fa-edit"></i>
                    Edit Profile</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('client.settings') }}"><i class="fa fa-cog"></i>
                    Settings</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
                        class="fa-solid fa-arrow-right-from-bracket"></i> Logout</a>
            </li>
        </ul>
        <a href="{{ route('helper.index') }}" class="btn btn-primary">Login as Helper</a>
    </nav>
</div>


<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>



<script>
    document.getElementById('sidebarToggle').addEventListener('click', function() {
        document.querySelector('.sidebar').classList.toggle('sidebar-hidden');
        document.querySelector('.navbar').classList.toggle('navbar-left');
        document.querySelector('footer').classList.toggle('footer-left');
        document.querySelector('main').classList.toggle('main-left');
    });

    // Onload if window.size is below 700px then call sidebarToggle
    window.onload = function() {
        if (window.innerWidth < 700) {
            document.getElementById('sidebarToggle').click();
        }
    }
</script>
