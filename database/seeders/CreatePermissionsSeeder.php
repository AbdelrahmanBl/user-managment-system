<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class CreatePermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::updateOrCreate(['name' => 'create']);
        Permission::updateOrCreate(['name' => 'update']);
        Permission::updateOrCreate(['name' => 'destroy']);
    }
}
