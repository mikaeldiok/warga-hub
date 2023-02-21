<?php

namespace Modules\Performance\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

//Events
Use Modules\Performance\Events\ParameterRegistered;

//Listeners
Use Modules\Performance\Listeners\NotifyParameter;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        ParameterRegistered::class => [
            NotifyParameter::class,
        ],
    ];
}
