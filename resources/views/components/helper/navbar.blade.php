<nav class="navbar navbar-left navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">

        <div class="d-flex align-items-center ">
            <button id="sidebarToggle" class="btn btn-sm btn-outline mx-3">
                <i class="fas fa-bars  fa-2x"></i>
            </button>

            <h5 class="m-0 d-none d-md-block">Helper Dashboard</h5>
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

{{-- Sidebar Code --}}
<div class="sidebar">
    <a class="navbar-brand" href="{{ route('index') }}">
        <img src="{{ config('website_logo') ? asset('images/logo/' . config('website_logo')) : asset('images/logo/icon.png') }}"
            alt="2 Point" width="50">
        2 Point Helper
    </a>
    <nav class="mt-3">
        <ul class="p-0">
            <li class="nav-item"><a class="nav-link" href="{{ route('helper.index') }}"><i class="fa fa-home"></i>
                    Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('helper.kyc_details') }}"><i
                        class="fa fa-bank"></i> KYC Detail</a>
            </li>
            @if (app('userInfoHelper')->hasHelperCompany())
                <li class="nav-item"><a class="nav-link" href="{{ route('helper.team.index') }}"><i
                            class="fa fa-users"></i> Teams</a></li>
            @endif
            <li class="nav-item"><a class="nav-link" href="{{ route('helper.bookings') }}"><i class="fa fa-dolly"></i>
                    Bookings</a></li>

            <li class="nav-item"><a class="nav-link" href="{{ route('helper.invitations') }}"><i
                        class="fa fa-dolly"></i> Invitations</a></li>

            <li class="nav-item"><a class="nav-link" href="{{ route('helper.trackOrder') }}"><i
                        class="fa fa-file-invoice"></i> Track Order</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('helper.chats') }}"><i class="fa fa-comment"></i>
                    Chat</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('helper.profile') }}"><i class="fa fa-edit"></i>
                    Edit Profile</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
                        class="fa-solid fa-arrow-right-from-bracket"></i> Logout</a>
            </li>
        </ul>
        <a href="{{ route('client.index') }}" class="btn btn-primary">Login as Client</a>
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
