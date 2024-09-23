<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\SystemSettingsProvider::class,
    App\Providers\DateTimeProvider::class,
    App\Providers\AddressProvider::class,
    App\Providers\UserInfoProvider::class,
    App\Providers\NotificationProvider::class,
    Barryvdh\DomPDF\ServiceProvider::class,
    App\Providers\BookingProvider::class,
];
