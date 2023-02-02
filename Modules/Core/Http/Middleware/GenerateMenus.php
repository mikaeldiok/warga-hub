<?php

namespace Modules\Core\Http\Middleware;

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
            //core menu

            $menu->add('<i class="fas fa-school c-sidebar-nav-icon"></i> '.trans('menu.core.units'), [
                'route' => 'backend.units.index',
                'class' => 'c-sidebar-nav-item',
            ])
            ->data([
                'order' => 2,
                'activematches' => ['admin/units*'],
                'permission' => ['view_units'],
            ])
            ->link->attr([
                'class' => 'c-sidebar-nav-link',
            ]);
            
        })->sortBy('order');

        return $next($request);
    }
}
