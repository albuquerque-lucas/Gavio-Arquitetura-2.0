<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Igor Gávio',
            'username' => 'igor.gavio',
            'description' => 'Arquiteto e Urbanista pelo Centro de Ensino de Superior Juiz de Fora. Igor é responsável pelo setor executivo, coordenação de projetos e processo criativo arquitetônico. Desenvolve projetos em que a preocupação estética esteja associada às melhores soluções técnicas e funcionais, proporcionando um ambiente confortável e com uma identidade única.',
            'email' => 'gavio.arquitetura@gmail.com',
            'password' => Hash::make('12345678'),
            'ownership' => true,
        ]);

        User::create([
            'name' => 'Isabelle Gávio',
            'username' => 'isabelle.gavio',
            'description' => 'Arquiteta e Urbanista pelo Centro de Ensino de Superior de Juiz de Fora e pós graduada em técnicas projetuais e BIM. Isabelle é responsável pelo setor criativo de interiores, administrativo e social media. Desenvolve projetos em que a preocupação estética esteja associada às melhores soluções técnicas e funcionais, proporcionando um ambiente confortável e com uma identidade única.',
            'email' => 'isabelle.gavio@gmail.com',
            'password' => Hash::make('12345678'),
            'ownership' => true,
        ]);
    }
}
