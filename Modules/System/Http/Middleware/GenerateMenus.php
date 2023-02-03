<?php

namespace Modules\System\Http\Middleware;

use Closure;

class GenerateMenus
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        \Menu::make('admin_sidebar', function ($menu) {
            //system menu

            // Separator: Mkstarer
            $menu->add('System', [
                'class' => 'c-sidebar-nav-title',
            ])
            ->data([
                'order'         => 2,
                'permission'    => ['view_appsite'],
            ]);

            $menu->add('<i class="fas fa-graduation-cap c-sidebar-nav-icon"></i> '.trans('menu.system.appsite'), [
                'route' => 'backend.appsites.index',
                'class' => 'c-sidebar-nav-item',
            ])
            ->data([
                'order' => 3,
                'activematches' => ['admin/appsite*'],
                'permission' => ['view_appsite'],
            ])
            ->link->attr([
                'class' => 'c-sidebar-nav-link',
            ]);

            $menu->add('<i class="fas fa-system c-sidebar-nav-icon"></i> '.trans('menu.system.cores'), [
                'route' => 'backend.cores.index',
                'class' => 'c-sidebar-nav-item',
            ])
            ->data([
                'order' => 4,
                'activematches' => ['admin/cores*'],
                'permission' => ['view_cores'],
            ])
            ->link->attr([
                'class' => 'c-sidebar-nav-link',
            ]);
        })->sortBy('order');

        return $next($request);
    }
}
