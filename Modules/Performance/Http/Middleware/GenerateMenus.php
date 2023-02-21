<?php

namespace Modules\Performance\Http\Middleware;

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
            //performance menu

            // Separator: Mkstarer
            $menu->add('Performance', [
                'class' => 'c-sidebar-nav-title',
            ])
            ->data([
                'order'         => 2,
                'permission'    => ['view_parameters'],
            ]);

            $menu->add('<i class="fas fa-graduation-cap c-sidebar-nav-icon"></i> '.trans('menu.performance.parameters'), [
                'route' => 'backend.parameters.index',
                'class' => 'c-sidebar-nav-item',
            ])
            ->data([
                'order' => 3,
                'activematches' => ['admin/parameters*'],
                'permission' => ['view_parameters'],
            ])
            ->link->attr([
                'class' => 'c-sidebar-nav-link',
            ]);

            $menu->add('<i class="fas fa-performance c-sidebar-nav-icon"></i> '.trans('menu.performance.cores'), [
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
