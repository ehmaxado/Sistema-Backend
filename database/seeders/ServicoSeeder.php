<?php

namespace Database\Seeders;

use Dev2\Models\Servico;
use Illuminate\Database\Seeder;

class ServicoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Servico::create(['nome' => 'Consulta', 'descricao' => 'Consulta com especialista', 'preco' => 100.00, 'status' => true]);
        Servico::create(['nome' => 'Limpeza', 'descricao' => 'Limpeza profunda', 'preco' => 50.00, 'status' => true]);
        Servico::create(['nome' => 'Manutenção', 'descricao' => 'Serviço de manutenção', 'preco' => 75.00, 'status' => false]);
    }
}
