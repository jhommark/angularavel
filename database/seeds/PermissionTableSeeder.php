<?php

use Illuminate\Database\Seeder;
use App\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->delete();
        $permissions = array(
        	['name' => 'view-users', 'display_name' => 'View Users', 'description' => 'Permission to view users'],
        	['name' => 'add-users', 'display_name' => 'Add Users', 'description' => 'Permission to add users'],
        	['name' => 'edit-users', 'display_name' => 'Edit Users', 'description' => 'Permission to edit users'],
        	['name' => 'delete-users', 'display_name' => 'Delete Users', 'description' => 'Permission to delete users']
        );
        // Loop through each permission above and create the record for them in the database
        foreach ($permissions as $permission)
        {
	        Permission::create($permission);
        }
    }
}
