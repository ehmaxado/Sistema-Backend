<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo 'Dev3 namespace check:' . PHP_EOL;
echo 'AgendamentoRequest exists: ' . (class_exists('Dev3\Requests\AgendamentoRequest') ? 'YES' : 'NO') . PHP_EOL;
echo 'Agendamento model exists: ' . (class_exists('Dev3\Models\Agendamento') ? 'YES' : 'NO') . PHP_EOL;
echo 'AgendamentoController exists: ' . (class_exists('Dev3\Controllers\AgendamentoController') ? 'YES' : 'NO') . PHP_EOL;
echo 'AgendamentoService exists: ' . (class_exists('Dev3\Services\AgendamentoService') ? 'YES' : 'NO') . PHP_EOL;
