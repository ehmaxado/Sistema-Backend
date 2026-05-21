<?php

use Illuminate\Support\Facades\Route;
use Dev1\Controllers\ClienteController;

Route::apiResource('clientes', ClienteController::class);

// Dev 2 adiciona as rotas de serviços aqui
// Dev 3 adiciona as rotas de agendamentos aqui
