<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;

class CreateAdminAccount extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admins = User::factory()->count(20)->create();
        foreach($admins as $admin) {
            $role_id = Role::where('name','ADMIN')->first();
            $admin->roles()->attach($role_id);

            $permission_ids = Permission::whereIn('name',['create','update','destroy'])->get();
            $admin->permissions()->attach($permission_ids);
        }
    }
}
