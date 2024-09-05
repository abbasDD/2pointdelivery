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
                    <li><a class="nav-link" href="{{ route('admin.admins') }}">Admins </a></li>
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
                    <li><a class="nav-link" href="{{ route('admin.vehicleTypes') }}">Vehicles </a></li>
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
                <a class="nav-link" href="{{ route('admin.reviews') }}"><i class="fa fa-dolly"></i>
                    Reviews</a>
            </li>

            {{-- Wallet --}}
            <li class="nav-item has-submenu">
                <a class="nav-link" href="#"> <i class="fa-solid fa-wallet"></i> Wallet </a>
                <ul class="submenu collapse">
                    <li>
                        <a class="nav-link" href="{{ route('admin.wallet') }}">
                            Statistics
                        </a>
                    </li>
                    <li>
                        <a class="nav-link" href="{{ route('admin.helper.BankAccounts') }}">
                            Bank Accounts
                        </a>
                    </li>
                    {{-- Received --}}
                    <li>
                        <a class="nav-link" href="{{ route('admin.wallet.received') }}">
                            Received
                        </a>
                    </li>
                    {{-- Refund --}}
                    <li>
                        <a class="nav-link" href="{{ route('admin.wallet.refund') }}">
                            Refund
                        </a>
                    </li>
                    {{-- Withdraw --}}
                    <li>
                        <a class="nav-link" href="{{ route('admin.wallet.withdraw') }}">
                            Withdraw
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.chats') }}"><i class="fa-solid fa-inbox"></i>
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
                    <li><a class="nav-link" href="{{ route('admin.emailTemplates.index') }}">Mailing </a>
                    <li><a class="nav-link" href="{{ route('admin.frontendSettings.index') }}">Policies </a>
                    </li>
                </ul>
            </li>
            {{-- Push Notification --}}
            <li class="nav-item has-submenu">
                <a class="nav-link" href="#"> <i class="fa fa-bell"></i> Notifications </a>
                <ul class="submenu collapse">
                    <li><a class="nav-link" href="{{ route('admin.pushNotification') }}">List </a></li>
                    <li><a class="nav-link" href="{{ route('admin.pushNotification.new') }}">New </a></li>
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
