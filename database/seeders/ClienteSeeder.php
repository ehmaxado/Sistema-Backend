<?php

namespace Database\Seeders;

use Dev1\Models\Cliente;
use Illuminate\Database\Seeder;

class ClienteSeeder extends Seeder
{
    public function run(): void
    {
        Cliente::create([
            'nome'     => 'João da Silva',
            'email'    => 'joao@example.com',
            'telefone' => '49999998888',
        ]);

        Cliente::create([
            'nome'     => 'Maria Souza',
            'email'    => 'maria@example.com',
            'telefone' => '49988887777',
        ]);

        Cliente::create([
            'nome'     => 'Carlos Pereira',
            'email'    => null,
            'telefone' => '49977776666',
        ]);
    }
}
