<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            for ($i = 1; $i <= 15; $i++) {
                Project::create([
                    'category_id' => $category->id,
                    'title' => "Projeto $i",
                    'description' => "Descrição $i",
                    'date' => '2020-01-01',
                    'location' => "Rua Teste $i Número 237, 33"
                ]);
            }
        }
    }
}
