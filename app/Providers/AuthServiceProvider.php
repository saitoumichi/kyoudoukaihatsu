<?php

namespace App\Providers;

use App\Models\Place;
use App\Models\FreeMarket;
use App\Policies\PlacePolicy;
use App\Policies\FreeMarketPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Place::class => PlacePolicy::class,
        FreeMarket::class => FreeMarketPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
