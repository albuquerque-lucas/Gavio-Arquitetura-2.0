<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = [
            [
                'category_id' => 1,
                'title' => "Casa CR",
                'description' => "Uma casa de 2 pavimentos projetada para atender um programa de necessidades de 4 quartos, sendo 2 suítes e 2 quartos com banheiro canadense, sala 2 ambientes integrada com cozinha e espaço gourmet com piscina, jacuzzi e sauna.",
                'area' => 430,
                'year' => 2021,
                'location' => "Alphaville, Juiz de Fora - MG",
            ],
            [
                'category_id' => 1,
                'title' => "Casa RF",
                'description' => null,
                'area' => null,
                'year' => 2021,
                'location' => "Cond. Granville, Juiz de Fora - MG",
            ],
            [
                'category_id' => 1,
                'title' => "Casa RM",
                'description' => "Projeto de fachada/interiores realizado para casa com 3 quartos, home cine, sala 2 ambientes, cozinha, área gourmet com piscina, sauna e brinquedoteca.",
                'area' => 480,
                'year' => 2020,
                'location' => "Cond. Nova Gramado, Juiz de Fora - MG",
            ],
            [
                'category_id' => 2,
                'title' => "Apartamento FC",
                'description' => "",
                'area' => 148,
                'year' => 2021,
                'location' => "Laranjeiras, Juiz de Fora - MG",
            ],
            [
                'category_id' => 2,
                'title' => "Apartamento DM",
                'description' => "Projeto de interiores realizado para apartamento com 3 quartos, sala 2 ambientes, cozinha e varanda gourmet.",
                'area' => 120,
                'year' => 2021,
                'location' => "São Mateus, Juiz de Fora - MG",
            ],
            [
                'category_id' => 2,
                'title' => "Casa PH",
                'description' => "",
                'area' => 215,
                'year' => 2021,
                'location' => "Bairro de Lourdes, Juiz de Fora - MG",
            ],
            [
                'category_id' => 3,
                'title' => "Salão MM Coiffer",
                'description' => "",
                'area' => null,
                'year' => 2020,
                'location' => "Mister Shopping, Juiz de Fora - MG",
            ],
            [
                'category_id' => 3,
                'title' => "Portaria Enseada",
                'description' => "Projeto de interiores realizado para hall de entrada e circulações de edifício residencial.",
                'area' => 35,
                'year' => 2021,
                'location' => "Cascatinha, Juiz de Fora - MG",
            ],
            [
                'category_id' => 3,
                'title' => "Postinho 032",
                'description' => "",
                'area' => null,
                'year' => 2020,
                'location' => "Av. Deusdedith Salgado, Juiz de Fora - MG",
            ],
        ];

        $additionalProjectsCount = 60;
        $categoryCount = 3;
        $projectsPerCategory = $additionalProjectsCount / $categoryCount;

        for ($i = 0; $i < $additionalProjectsCount; $i++) {
            $category_id = ($i % $categoryCount) + 1;
            Project::create([
                'category_id' => $category_id,
                'title' => "Projeto " . ($i + 1),
                'description' => "Descrição do Projeto " . ($i + 1),
                'area' => 200,
                'year' => 2021,
                'location' => "Localização do Projeto " . ($i + 1),
                'order' => count($projects) + $i + 1
            ]);
}

        foreach ($projects as $index => $project) {
            Project::create(array_merge($project, ['order' => $index + 1]));
        }
    }
}
