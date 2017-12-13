<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\User;

class RoleUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('role_user')->delete();
        $user = User::where('email', '=', 'jhommark@gmail.com')->first();
        $role = Role::where('name', '=', 'admin')->first();
        $user->roles()->attach($role->id);
    }
}
