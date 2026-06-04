<?php

namespace Database\Seeders;

use Dev3\Models\Agendamento;
use Illuminate\Database\Seeder;

class AgendamentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Agendamento::create(['cliente_id' => 1, 'servico_id' => 1, 'data' => '2025-06-10', 'hora' => '09:00', 'status' => 'agendado']);
        Agendamento::create(['cliente_id' => 2, 'servico_id' => 2, 'data' => '2025-06-10', 'hora' => '10:00', 'status' => 'agendado']);
        Agendamento::create(['cliente_id' => 1, 'servico_id' => 1, 'data' => '2025-06-05', 'hora' => '14:00', 'status' => 'realizado']);
    }
}
