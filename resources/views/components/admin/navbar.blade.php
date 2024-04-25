<nav class="navbar navbar-left navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">

        <div class="d-flex align-items-center ">
            <button id="sidebarToggle" class="btn btn-sm btn-outline mx-3">
                <i class="fas fa-bars"></i>
            </button>

            <h5 class="m-0 d-none d-md-block">Admin Dashboard</h5>
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

{{-- Sidebar Code her --}}
<div class="sidebar">
    <a class="navbar-brand" href="{{ route('index') }}">
        <img src="{{ asset('images/logo/icon.png') }}" alt="2 Point" height="30">
        2 Point Admin
    </a>
    <nav class="mt-5">
        <ul class="p-0">
            <li class="nav-item"><a class="nav-link" href="{{ route('admin.index') }}"><i class="fa fa-home"></i>
                    Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('admin.admins') }}"><i class="fa fa-bank"></i>
                    Sub Admins</a>
            </li>
            <li class="nav-item has-submenu">
                <a class="nav-link" href="#"> <i class="fa fa-users"></i> Users </a>
                <ul class="submenu collapse">
                    <li><a class="nav-link" href="{{ route('admin.clients') }}">Clients </a></li>
                    <li><a class="nav-link" href="{{ route('admin.helpers') }}">Helpers </a></li>
                    <li><a class="nav-link" href="{{ route('admin.requestedHelpers') }}">Requested Helpers </a></li>
                </ul>
            </li>
            <li class="nav-item"><a class="nav-link" href="{{ route('admin.bookings') }}"><i class="fa fa-dolly"></i>
                    Bookings</a></li>
            <li class="nav-item"><a class="nav-link" href="#"><i class="fa fa-comment"></i> Chat</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('admin.vehicleTypes') }}"><i
                        class="fa fa-edit"></i> Vehicles</a>
            </li>
            <li class="nav-item"><a class="nav-link" href="{{ route('admin.serviceCategories') }}"><i
                        class="fa fa-user"></i> Service Categories </a></li>
            <li class="nav-item has-submenu">
                <a class="nav-link" href="#"> <i class="fa fa-cog"></i> Settings </a>
                <ul class="submenu collapse">
                    <li><a class="nav-link" href="{{ route('admin.systemSettings') }}">System </a></li>
                    <li><a class="nav-link" href="{{ route('admin.taxSettings') }}">Tax </a></li>
                    <li><a class="nav-link" href="{{ route('admin.systemSettings') }}">Payment </a> </li>
                    <li><a class="nav-link" href="{{ route('admin.systemSettings') }}">Priority </a> </li>
                </ul>
            </li>
            <li class="nav-item"><a class="nav-link" href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
                        class="fa-solid fa-arrow-right-from-bracket"></i> Logout</a>
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

    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('.sidebar .nav-link').forEach(function(element) {

            element.addEventListener('click', function(e) {

                let nextEl = element.nextElementSibling;
                let parentEl = element.parentElement;

                if (nextEl) {
                    e.preventDefault();
                    let mycollapse = new bootstrap.Collapse(nextEl);

                    if (nextEl.classList.contains('show')) {
                        mycollapse.hide();
                    } else {
                        mycollapse.show();
                        // find other submenus with class=show
                        var opened_submenu = parentEl.parentElement.querySelector(
                            '.submenu.show');
                        // if it exists, then close all of them
                        if (opened_submenu) {
                            new bootstrap.Collapse(opened_submenu);
                        }
                    }
                }
            }); // addEventListener
        }) // forEach
    });
    // DOMContentLoaded  end
</script>
