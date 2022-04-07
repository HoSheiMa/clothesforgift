<?php

namespace App\Providers;

use App\Models\products;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define("update-product", function (
            User $user,
            products $product
        ) {
            return Auth::user()->role == "admin" ||
                (Auth::user()->role == "seller" &&
                    $product->created_by == Auth::user()->id);
        });
    }
}