<?php

use Illuminate\Database\Seeder;
use App\Permission;
use App\Role;

class PermissionRoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permission_role')->delete();
        $role = Role::where('name', '=', 'admin')->first();
        $permissionNames = ['view-users', 'add-users', 'edit-users', 'delete-users'];
        foreach ($permissionNames as $permissionName)
        {
        	$permission = Permission::where('name', '=', $permissionName)->first();
        	$role->attachPermission($permission);
        }
    }
}
