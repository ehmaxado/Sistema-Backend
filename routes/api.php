<?php

use Illuminate\Support\Facades\Route;
use Dev1\Controllers\ClienteController;
use Dev2\Controllers\ServicoController;

Route::apiResource('clientes', ClienteController::class);

Route::patch('servicos/{id}/status', [ServicoController::class, 'toggleStatus']);
Route::apiResource('servicos', ServicoController::class);

// Dev 3 adiciona as rotas de agendamentos aqui
