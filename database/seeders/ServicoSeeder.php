<?php

namespace Database\Seeders;

use Dev2\Models\Servico;
use Illuminate\Database\Seeder;

class ServicoSeeder extends Seeder
{
    public function run(): void
    {
        Servico::create([
            'nome' => 'Consulta nutricional',
            'descricao' => 'Avaliação completa',
            'duracao_minutos' => 60,
            'valor' => 150.00,
            'status' => true,
        ]);

        Servico::create([
            'nome' => 'Reavaliação',
            'descricao' => 'Acompanhamento',
            'duracao_minutos' => 30,
            'valor' => 80.00,
            'status' => true,
        ]);

        Servico::create([
            'nome' => 'Serviço descontinuado',
            'descricao' => null,
            'duracao_minutos' => 45,
            'valor' => 100.00,
            'status' => false,
        ]);
    }
}
