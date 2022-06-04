<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Skill;

class createSkillsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Skill::insert([
            [
                'name' => 'php'
            ],
            [
                'name' => 'node js'
            ],
            [
                'name' => 'python'
            ],
            [
                'name' => 'asp.net'
            ],
            [
                'name' => 'ruby'
            ],
            [
                'name' => 'html'
            ],
            [
                'name' => 'css'
            ],
            [
                'name' => 'vue js'
            ],
            [
                'name' => 'react js'
            ]
        ]);
    }
}
