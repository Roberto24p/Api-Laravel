<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Passport::tokensCan([
            'get-groups' => 'Get all groups',
            'get-directings' => 'Get all directings'
        ]);
        // Passport::tokensCan([
        //     'token-superadmin' => 'Get all groups',
        //     'token-admin' => 'Get all directings',
        //     'token-secretaria' => 'Get all directings',
        //     'token-dirgroup' => 'Get all directings',
        //     'token-dirunit' => 'Get all directings',
        //     'token-scout' => 'Get all directings'
        // ]);
        Passport::routes();
        //
    }
}
