<nav class="navbar navbar-left navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">

        <div class="d-flex align-items-center ">
            <button id="sidebarToggle" class="btn btn-outline">
                <i class="fas fa-bars"></i>
            </button>

            <h5 class="m-0 d-none d-md-block">Client Dashboard</h5>
        </div>

        <div class="d-flex">
            @auth
                <div class="dropdown">
                    <p class="btn btn-link nav-link dropdown-toggle mb-0" type="button" id="dropdownMenuButton"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <img class="user-image d-inline rounded-circle" src="{{ asset('images/default-user.jpg') }}"
                            width="35" height="35" alt="User">
                    </p>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li><a class="dropdown-item" href="#">Profile</a></li>
                        <li><a class="dropdown-item" href="#">Messages</a></li>
                        <li><a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                        </li>
                    </ul>
                </div>
            @else
                <!-- If user is not logged in, show sign in button -->
                <a href="{{ route('client.login') }}" class="nav-link"><i class="fa-regular fa-user mx-2"></i>Sign In</a>
            @endauth
        </div>
    </div>
</nav>
<div class="sidebar">
    <a class="navbar-brand" href="{{ route('index') }}">
        <img src="{{ asset('images/logo/icon.png') }}" alt="2 Point" height="30">
        2 Point Client
    </a>
    <nav class="mt-5">
        <ul class="p-0">
            <li class="nav-link"><i class="fa fa-home"></i> <a href="{{ route('client.index') }}">Dashboard</a></li>
            <li class="nav-link"><i class="fa fa-bank"></i> <a href="{{ route('client.kyc_details') }}">KYC Detail</a>
            </li>
            @if (Auth::user()->account_type == 'company')
                <li class="nav-link"><i class="fa fa-users"></i> <a href="#">Teams</a></li>
            @endif
            <li class="nav-link"><i class="fa fa-dolly"></i> <a href="{{ route('client.orders') }}">Orders</a></li>
            <li class="nav-link"><i class="fa fa-file-invoice"></i> <a
                    href="{{ route('client.invoices') }}">Invoices</a></li>
            <li class="nav-link"><i class="fa fa-comment"></i> <a href="#">Chat</a></li>
            <li class="nav-link"><i class="fa-solid fa-repeat"></i> <a
                    href="{{ route('client.referrals') }}">Referrals</a></li>
            <li class="nav-link"><i class="fa fa-edit"></i> <a href="{{ route('client.edit') }}">Edit Profile</a></li>
            <li class="nav-link"><i class="fa fa-cog"></i> <a href="{{ route('client.settings') }}">Settings</a></li>
            <li class="nav-link"><i class="fa-solid fa-arrow-right-from-bracket"></i> <a href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
            </li>
        </ul>
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
</script>
