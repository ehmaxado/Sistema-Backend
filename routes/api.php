<?php

use Illuminate\Support\Facades\Route;
use Dev1\Controllers\ClienteController;
use Dev2\Controllers\ServicoController;
use Dev3\Controllers\AgendamentoController;

Route::apiResource('clientes', ClienteController::class);

Route::patch('servicos/{id}/status', [ServicoController::class, 'toggleStatus']);
Route::apiResource('servicos', ServicoController::class);

Route::apiResource('agendamentos', AgendamentoController::class)->except(['update']);
Route::patch('agendamentos/{id}/status', [AgendamentoController::class, 'updateStatus']);
