<?php

namespace Database\Seeders\Auth;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

/**
 * Class PermissionRoleTableSeeder.
 */
class PermissionRoleTableSeeder extends Seeder
{
    /**
     * Run the database seed.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        // Create Roles
        $super_admin = Role::firstOrCreate(['name' => 'super admin']);
        $admin = Role::firstOrCreate(['name' => 'administrator']);
        $manager = Role::firstOrCreate(['name' => 'manager']);
        $executive = Role::firstOrCreate(['name' => 'executive']);
        $user = Role::firstOrCreate(['name' => 'user']);

        // Create Permissions
        Permission::firstOrCreate(['name' => 'view_backend']);
        Permission::firstOrCreate(['name' => 'edit_settings']);
        Permission::firstOrCreate(['name' => 'view_logs']);

        $permissions = Permission::defaultPermissions();

        foreach ($permissions as $perms) {
            Permission::firstOrCreate(['name' => $perms]);
        }

        \Artisan::call('auth:permission', [
            'name' => 'posts',
        ]);
        echo "\n _Posts_ Permissions Created.";

        \Artisan::call('auth:permission', [
            'name' => 'categories',
        ]);
        echo "\n _Categories_ Permissions Created.";

        \Artisan::call('auth:permission', [
            'name' => 'tags',
        ]);
        echo "\n _Tags_ Permissions Created.";

        \Artisan::call('auth:permission', [
            'name' => 'comments',
        ]);
        echo "\n _Comments_ Permissions Created.";

        \Artisan::call('auth:permission', [
            'name' => 'students',
        ]);
        echo "\n _Students_ Permissions Created.";

        \Artisan::call('auth:permission', [
            'name' => 'mkdums',
        ]);
        echo "\n _Mkdums_ Permissions Created.";

        \Artisan::call('auth:permission', [
            'name' => 'appsites',
        ]);
        echo "\n _Appsites_ Permissions Created.";

        \Artisan::call('auth:permission', [
            'name' => 'groups',
        ]);
        echo "\n _Groups_ Permissions Created.";

        \Artisan::call('auth:permission', [
            'name' => 'fees',
        ]);
        echo "\n _Fees_ Permissions Created.";

        \Artisan::call('auth:permission', [
            'name' => 'units',
        ]);
        echo "\n _Units_ Permissions Created.";

        \Artisan::call('auth:permission', [
            'name' => 'subunits',
        ]);
        echo "\n _Subunits_ Permissions Created.";

        \Artisan::call('auth:permission', [
            'name' => 'parameters',
        ]);
        echo "\n _Parameters_ Permissions Created.";

        echo "\n\n";

        // Assign Permissions to Roles
        $admin->givePermissionTo(Permission::all());
        $manager->givePermissionTo('view_backend');
        $executive->givePermissionTo('view_backend');

        Schema::enableForeignKeyConstraints();
    }
}
