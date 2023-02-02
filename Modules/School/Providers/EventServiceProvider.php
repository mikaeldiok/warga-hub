<?php

namespace Modules\School\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

//Events
Use Modules\School\Events\StudentRegistered;

//Listeners
Use Modules\School\Listeners\NotifyStudent;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        StudentRegistered::class => [
            NotifyStudent::class,
        ],
    ];
}
