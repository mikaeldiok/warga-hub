<?php

namespace Modules\Data\Http\Middleware;

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
            //data menu

            // Separator: Mkstarer
            $menu->add('Data', [
                'class' => 'c-sidebar-nav-title',
            ])
            ->data([
                'order'         => 5,
                'permission'    => ['view_units'],
            ]);

            $menu->add('<i class="fas fa-graduation-cap c-sidebar-nav-icon"></i> '.trans('menu.data.units'), [
                'route' => 'backend.units.index',
                'class' => 'c-sidebar-nav-item',
            ])
            ->data([
                'order' => 6,
                'activematches' => ['admin/units*'],
                'permission' => ['view_units'],
            ])
            ->link->attr([
                'class' => 'c-sidebar-nav-link',
            ]);

            $menu->add('<i class="fas fa-graduation-cap c-sidebar-nav-icon"></i> '.trans('menu.data.subunits'), [
                'route' => 'backend.subunits.index',
                'class' => 'c-sidebar-nav-item',
            ])
            ->data([
                'order' => 6,
                'activematches' => ['admin/subunits*'],
                'permission' => ['view_subunits'],
            ])
            ->link->attr([
                'class' => 'c-sidebar-nav-link',
            ]);
        })->sortBy('order');

        return $next($request);
    }
}
