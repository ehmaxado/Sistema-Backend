<?php

use Illuminate\Support\Facades\Route;
use Dev1\Controllers\ClienteController;
use Dev3\Controllers\AgendamentoController;

Route::apiResource('clientes', ClienteController::class);

// Dev 2 adiciona as rotas de serviços aqui
// Dev 3 adiciona as rotas de agendamentos aqui

Route::apiResource('agendamentos', AgendamentoController::class)->except(['update']);
Route::patch('agendamentos/{id}/status', [AgendamentoController::class, 'updateStatus']);
