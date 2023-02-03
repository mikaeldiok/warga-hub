<?php

namespace Modules\System\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

//Events
Use Modules\System\Events\AppsiteRegistered;

//Listeners
Use Modules\System\Listeners\NotifyAppsite;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        AppsiteRegistered::class => [
            NotifyAppsite::class,
        ],
    ];
}
