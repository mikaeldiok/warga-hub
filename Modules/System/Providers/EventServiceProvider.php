<?php

namespace Modules\Mkstarter\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

//Events
Use Modules\Mkstarter\Events\MkdumRegistered;

//Listeners
Use Modules\Mkstarter\Listeners\NotifyMkdum;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        MkdumRegistered::class => [
            NotifyMkdum::class,
        ],
    ];
}
