<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Project;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();

        foreach ($categories as $category) {
            $order = 1;
            for ($i = 1; $i <= 15; $i++) {
                Project::create([
                    'category_id' => $category->id,
                    'title' => "Projeto $i",
                    'description' => "Descrição $i",
                    'area' => "20$i",
                    'year' => 2020,
                    'location' => "Rua Teste $i Número 237, 33",
                    'order' => $order,
                ]);
                $order++;
            }
        }
    }
}
