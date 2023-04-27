<?php

namespace App\Providers;

use App\Models\Activity;
use App\Models\Admin;
use App\Policies\AdminPolicy;
use App\Policies\LogsPolicy;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{

    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Admin::class    => AdminPolicy::class,
        Activity::class => LogsPolicy::class,
    ];

    /**
     * Register services.
     */
    public function register()
    {
		//
	}

    /**
     * Bootstrap services.
     */
    public function boot(Gate $gate)
    {
        $this->registerPolicies();

        $this->registerPrivileges($gate);
    }

    /**
     * Menu View privileges mert minden modellhez policyt kell csinálni majd minden controller minden functionjébe beletenni :D
     *
     * @param Gate $gate
     * @param RoleBasedAccessControlService $accessControlService
     */
    protected function registerPrivileges(Gate $gate)
    {
        $gate->before(function ($admin) {
            if($admin instanceof Admin && $admin->hasRole('superadmin')) return true;
        });

        $gate->define('isSuperadmin', fn($admin) => $admin->hasRole('superadmin'));
    }
}
