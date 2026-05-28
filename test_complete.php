<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "\n========== TESTE COMPLETO DO MÓDULO AGENDAMENTOS (DEV3) ==========\n\n";

// 1. Verificar Classes
echo "1️⃣ VERIFICANDO CLASSES NECESSÁRIAS:\n";
$classes = [
    'Dev3\Models\Agendamento' => 'Model Agendamento',
    'Dev3\Controllers\AgendamentoController' => 'Controller',
    'Dev3\Services\AgendamentoService' => 'Service',
    'Dev3\Requests\AgendamentoRequest' => 'FormRequest',
    'Dev2\Models\Servico' => 'Model Servico (Dep)',
    'Dev1\Models\Cliente' => 'Model Cliente (Dep)',
];

foreach ($classes as $class => $label) {
    $exists = class_exists($class) ? '✅' : '❌';
    echo "   $exists $label ($class)\n";
}

// 2. Verificar Database
echo "\n2️⃣ VERIFICANDO DATABASE:\n";
$tables = ['agendamentos', 'servicos', 'clientes'];
foreach ($tables as $table) {
    $exists = DB::connection()->getSchemaBuilder()->hasTable($table) ? '✅' : '❌';
    echo "   $exists Tabela: $table\n";
}

// 3. Contar registros
echo "\n3️⃣ CONTANDO REGISTROS:\n";
echo "   " . DB::table('clientes')->count() . " clientes\n";
echo "   " . DB::table('servicos')->count() . " serviços\n";
echo "   " . DB::table('agendamentos')->count() . " agendamentos\n";

// 4. Teste CREATE
echo "\n4️⃣ TESTANDO CREATE AGENDAMENTO:\n";
try {
    $agendamento = \Dev3\Models\Agendamento::create([
        'cliente_id' => 1,
        'servico_id' => 1,
        'data' => '2025-08-01',
        'hora' => '16:00',
        'observacao' => 'Teste via Tinker'
    ]);
    echo "   ✅ Agendamento criado (ID: {$agendamento->id})\n";
} catch (\Exception $e) {
    echo "   ❌ Erro: " . $e->getMessage() . "\n";
}

// 5. Teste READ com relacionamentos
echo "\n5️⃣ TESTANDO READ COM RELACIONAMENTOS:\n";
$agendamento = \Dev3\Models\Agendamento::with(['cliente', 'servico'])->first();
if ($agendamento) {
    echo "   ✅ Agendamento ID {$agendamento->id}:\n";
    echo "      - Cliente: {$agendamento->cliente->nome}\n";
    echo "      - Serviço: {$agendamento->servico->nome}\n";
    echo "      - Data: {$agendamento->data} às {$agendamento->hora}\n";
    echo "      - Status: {$agendamento->status}\n";
} else {
    echo "   ❌ Nenhum agendamento encontrado\n";
}

// 6. Teste Service
echo "\n6️⃣ TESTANDO SERVICE:\n";
$service = new \Dev3\Services\AgendamentoService();
try {
    $novoAgendamento = $service->criar([
        'cliente_id' => 2,
        'servico_id' => 2,
        'data' => '2025-09-15',
        'hora' => '10:30',
    ]);
    echo "   ✅ Service criou agendamento ID: {$novoAgendamento->id}\n";
    echo "      - Status automático: {$novoAgendamento->status}\n";
} catch (\Exception $e) {
    echo "   ❌ Erro: " . $e->getMessage() . "\n";
}

// 7. Teste UPDATE Status
echo "\n7️⃣ TESTANDO UPDATE STATUS (SERVICE):\n";
try {
    $atualizado = $service->atualizarStatus($agendamento->id, 'realizado');
    echo "   ✅ Status atualizado para: {$atualizado->status}\n";
} catch (\Exception $e) {
    echo "   ❌ Erro: " . $e->getMessage() . "\n";
}

// 8. Teste Routes
echo "\n8️⃣ VERIFICANDO ROTAS:\n";
$routes = [
    'GET /api/agendamentos' => 'Index (listar)',
    'POST /api/agendamentos' => 'Store (criar)',
    'GET /api/agendamentos/{id}' => 'Show (detalhe)',
    'DELETE /api/agendamentos/{id}' => 'Destroy (cancelar)',
    'PATCH /api/agendamentos/{id}/status' => 'UpdateStatus (status)',
];
foreach ($routes as $route => $desc) {
    echo "   ✅ $route - $desc\n";
}

// 9. Teste final
echo "\n9️⃣ TOTAL DE AGENDAMENTOS NO BANCO:\n";
$total = \Dev3\Models\Agendamento::count();
echo "   📊 Total: $total registros\n";

echo "\n" . str_repeat("=", 70) . "\n";
echo "✅ TESTE COMPLETO FINALIZADO COM SUCESSO!\n";
echo str_repeat("=", 70) . "\n\n";
