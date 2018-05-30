<?php
/**
 * Copyright (c) 2018 - Clarastars, LLC - All Rights Reserved.
 *
 * Unauthorized copying of this file via any medium is strictly prohibited.
 * This file is a proprietary of Clarastars LLC and is confidential.
 *
 * https://clarastars.com - info@clarastars.com
 *
 */

namespace App\Providers;

use App\Events\PurchaseOrderSaved;
use App\Listeners\RecalculatePurchaseOrdersTotals;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        //'App\Events\Event' => [
        //    'App\Listeners\EventListener',
        //],
        //'Adldap\Laravel\Events\AuthenticationSuccessful' => [
        //    'App\Listeners\Adldap\AdldapAuthenticationSuccessful'
        //],
        PurchaseOrderSaved::class => [
            'App\Listeners\RecalculatePurchaseOrdersTotals',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
