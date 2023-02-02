<?php

namespace Modules\School\Http\Middleware;

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
            //school menu

            // Separator: Donasi
            $menu->add('Sekolah', [
                'class' => 'c-sidebar-nav-title',
            ])
            ->data([
                'order'         => 2,
                'permission'    => ['view_students'],
            ]);

            $menu->add('<i class="fas fa-graduation-cap c-sidebar-nav-icon"></i> '.trans('menu.school.students'), [
                'route' => 'backend.students.index',
                'class' => 'c-sidebar-nav-item',
            ])
            ->data([
                'order' => 3,
                'activematches' => ['admin/students*'],
                'permission' => ['view_students'],
            ])
            ->link->attr([
                'class' => 'c-sidebar-nav-link',
            ]);

            $menu->add('<i class="fas fa-school c-sidebar-nav-icon"></i> '.trans('menu.school.cores'), [
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
