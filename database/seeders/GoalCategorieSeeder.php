<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GoalCategorieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('goal_categories')->insert([
            'name' => 'VIVIENDA',
            'icon' => 'fa-home',
            'color' => '#F00C0C',
        ]);

        DB::table('goal_categories')->insert([
            'name' => 'MOTO',
            'icon' => 'fa-motorcycle',
            'color' => '#0CF0B2',
        ]);

        DB::table('goal_categories')->insert([
            'name' => 'CARRO',
            'icon' => 'fa-car',
            'color' => '#F0580C',
        ]);

        DB::table('goal_categories')->insert([
            'name' => 'MI EMPRESA',
            'icon' => 'fa-university',
            'color' => '#988E89',
        ]);

        DB::table('goal_categories')->insert([
            'name' => 'MIS ESTUDIOS',
            'icon' => 'fa-book',
            'color' => '#52EA3A',
        ]);

        DB::table('goal_categories')->insert([
            'name' => 'AHORRO LIBRE',
            'icon' => 'fa-balance-scale',
            'color' => '#F2CE03',
        ]); 
        //viajes
        //muebles
    }
}
