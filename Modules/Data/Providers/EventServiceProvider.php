<?php

namespace Modules\Data\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

//Events
Use Modules\Data\Events\UnitRegistered;

//Listeners
Use Modules\Data\Listeners\NotifyUnit;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        UnitRegistered::class => [
            NotifyUnit::class,
        ],
    ];
}
