<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Core;
use App\Models\JustificationStatuses;
use App\Models\Position;
use App\Models\EvaluationTypes;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DCPSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Department::create([
            'name' => 'Operativo'
        ]);
        
        Core::create([
            'name' => 'Sistemas',
            'department_id' => 1
        ]);

        Position::create([
            'name' => 'Backend',
            'core_id' => 1
        ]);

        JustificationStatuses::create([
            'name' => 'Aceptado',
        ]);

        JustificationStatuses::create([
            'name' => 'Rechazado',
        ]);

        JustificationStatuses::create([
            'name' => 'En Proceso',
        ]); 

        EvaluationTypes::create([
            'name' => 'Softskills',
        ]);
        
    }
}
