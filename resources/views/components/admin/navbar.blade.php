<nav class="navbar navbar-left navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">

        <div class="d-flex align-items-center">
            <button id="sidebarToggle" class="btn btn-sm btn-outline mx-3">
                <i class="fas fa-bars  fa-2x"></i>
            </button>

            <h5 class="m-0 d-none d-md-block">Admin Dashboard</h5>
        </div>

        <div class="d-flex">
            @auth
                {{-- Notification Dropdown --}}
                <div class="dropdown dropdown-notifications">
                    <p class="nav-link dropdown-toggle mb-0" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <span class="position-relative">

                            <i class="fa-regular fa-bell fs-24"></i>
                            @if (1)
                                <span id="notification-count"
                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    0
                                </span>
                            @endif
                        </span>
                    </p>
                    <ul id="notification-list" class="dropdown-menu dropdown-notification"
                        aria-labelledby="dropdownMenuButton">
                        <!-- Notifications will be dynamically loaded here -->
                    </ul>
                </div>

                {{-- User Dropdown --}}
                <div class="dropdown d-none d-md-block">
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
        2 Point Admin
    </a>
    <nav class="mt-3">
        <ul class="p-0">
            <li class="nav-item"><a class="nav-link" href="{{ route('admin.index') }}"><i class="fa fa-home"></i>
                    Dashboard</a></li>
            {{-- Users --}}
            <li class="nav-item has-submenu">
                <a class="nav-link" href="#"> <i class="fa fa-users"></i> Users </a>
                <ul class="submenu collapse">
                    <li><a class="nav-link" href="{{ route('admin.admins') }}">Sub Admins </a></li>
                    <li><a class="nav-link" href="{{ route('admin.clients') }}">Clients </a></li>
                    <li><a class="nav-link" href="{{ route('admin.helpers') }}">Helpers </a></li>
                    <li><a class="nav-link" href="{{ route('admin.newHelpers') }}">New Helpers </a></li>
                </ul>
            </li>
            {{-- Services --}}
            <li class="nav-item has-submenu">
                <a class="nav-link" href="#"> <i class="fa fa-dolly"></i> Services </a>
                <ul class="submenu collapse">
                    <li><a class="nav-link" href="{{ route('admin.serviceTypes') }}">Services </a></li>
                    <li><a class="nav-link" href="{{ route('admin.vehicleTypes') }}">Vehicle Types </a></li>
                    <li><a class="nav-link" href="{{ route('admin.serviceCategories') }}">Categories </a></li>
                </ul>
            </li>
            {{-- KYC --}}
            <li class="nav-item has-submenu">
                <a class="nav-link" href="#"> <i class="fa-solid fa-bank"></i> KYC </a>
                <ul class="submenu collapse">
                    <li>
                        <a class="nav-link" href="{{ route('admin.kycTypes') }}">
                            Types
                        </a>
                    </li>
                    <li>
                        <a class="nav-link" href="{{ route('admin.kycDetails') }}">
                            Details
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.bookings') }}"><i class="fa fa-dolly"></i>
                    Bookings</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.chats') }}"><i class="fa fa-comment"></i>
                    Chat</a>
            </li>

            {{-- Configs --}}
            <li class="nav-item has-submenu">
                <a class="nav-link" href="#"> <i class="fa fa-sliders"></i> Configs </a>
                <ul class="submenu collapse">
                    <li><a class="nav-link" href="{{ route('admin.movingConfig.index') }}">Moving </a></li>
                    <li><a class="nav-link" href="{{ route('admin.deliveryConfig.index') }}">Deliver </a></li>
                </ul>
            </li>
            {{-- Tools --}}
            <li class="nav-item has-submenu">
                <a class="nav-link" href="#"> <i class="fa fa-tools"></i> Tools </a>
                <ul class="submenu collapse">
                    <li><a class="nav-link" href="{{ route('admin.faqs') }}">FAQs </a></li>
                    <li><a class="nav-link" href="{{ route('admin.blogs') }}">Blogs </a></li>
                    <li><a class="nav-link" href="{{ route('admin.helpQuestions') }}">Help </a></li>
                    <li><a class="nav-link" href="{{ route('admin.emailTemplates.index') }}">Email Templates </a>
                    <li><a class="nav-link" href="{{ route('admin.frontendSettings.index') }}">Frontend Settings </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item"><a class="nav-link" href="{{ route('admin.settings') }}"><i class="fa fa-cog"></i>
                    Settings</a></li>
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

    // Onload if window.size is below 700px then call sidebarToggle
    window.onload = function() {
        if (window.innerWidth < 700) {
            document.getElementById('sidebarToggle').click();
        }

        // Load notifications initially
        fetchNotifications();

        // Poll for new notifications every 10 seconds
        setInterval(fetchNotifications, 10000);

    }

    function fetchNotifications() {
        // url
        var url = "{{ route('user.notifications') }}";
        $.ajax({
            url: url,
            method: 'GET',
            success: function(data) {
                const notificationList = $('#notification-list');
                notificationList.empty();
                notifications = data.notifications;
                // if data is empty
                if (data.notifications.length == 0) {
                    const notificationItem = $('<li class="item"></li>');
                    notificationItem.html(`
                        <h5>No new notifications</h5>
                    `);
                    notificationList.append(notificationItem);
                }
                notifications.forEach(notification => {
                    addNotification(notification);
                });
                updateNotificationCount(data.unread_notification);
            }
        });
    }

    function addNotification(notification) {
        const notificationList = $('#notification-list');
        var notificationItem;

        if (notification.read == 0) {
            notificationItem = $('<li class="item bg-light"></li>');
        } else {
            notificationItem = $('<li class="item"></li>');
        }

        var notificationID = notification.id;

        notificationItem.html(`
        <a href="{{ route('user.notificationRedirect', ['id' => '__notificationID__']) }}" class="nav-link">
            <h5>${notification.title}</h5>
            <p>${notification.content}</p>
        </a>
    `.replace('__notificationID__', notificationID)); // Replace placeholder with actual ID

        notificationList.prepend(notificationItem); // Add new notifications to the top
    }

    function updateNotificationCount(count) {
        const notificationCount = $('#notification-count');
        notificationCount.text(count);
    }
</script>
