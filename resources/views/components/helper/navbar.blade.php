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
                    <ul class="dropdown-menu dropdown-notification" aria-labelledby="dropdownMenuButton">
                        <a href="{{ route('user.notifications.read') }}" class="d-flex fs-xxs justify-content-end p-2">Mark
                            all as read</a>
                        <!-- Notifications will be dynamically loaded here -->
                        <div id="notification-list"></div>
                    </ul>
                </div>

                {{-- User Dropdown --}}


                {{-- Load user-dropdown.blade.php --}}
                @include('components.user-dropdown')

                {{-- End user-dropdown.blade.php --}}
            @else
                <!-- If user is not logged in, show sign in button -->
                <a href="{{ route('client.login') }}" class="nav-link"><i class="fa-regular fa-user mx-2"></i>Sign
                    In</a>
            @endauth
        </div>
    </div>
</nav>

{{-- Sidebar Code --}}
@include('components.helper.sidebar')



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
