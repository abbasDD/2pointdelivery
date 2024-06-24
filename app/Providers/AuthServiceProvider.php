<?php
// app/Providers/AuthServiceProvider.php
namespace App\Providers;

use App\Models\TeamInvitation;
use App\Policies\TeamInvitationPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        TeamInvitation::class => TeamInvitationPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}
