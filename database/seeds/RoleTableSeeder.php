<?php

use Illuminate\Database\Seeder;
use App\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->delete();
        $roles = array(
        	['name' => 'admin', 'display_name' => 'Administrator', 'description' => 'Administrator Role']
        );
        // Loop through each role above and create the record for them in the database
        foreach ($roles as $role)
        {
	        Role::create($role);
        }
    }
}
