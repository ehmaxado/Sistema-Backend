<?php

use Illuminate\Support\Facades\Route;
use Dev1\Controllers\ClienteController;
use Dev3\Controllers\AgendamentoController;
use Dev3\Controllers\TestController;
use Illuminate\Http\Request;

Route::apiResource('clientes', ClienteController::class);

// Dev 2 adiciona as rotas de serviços aqui
// Dev 3 adiciona as rotas de agendamentos aqui

Route::post('test', [TestController::class, 'test']);

Route::post('/direct-test', function (Request $request) {
    return response()->json([
        'raw_body' => $request->getContent(),
        'all_data' => $request->all(),
        'json_data' => $request->json()->all(),
        'headers' => [
            'Content-Type' => $request->header('Content-Type'),
            'Accept' => $request->header('Accept'),
        ]
    ]);
});

Route::apiResource('agendamentos', AgendamentoController::class)->except(['update']);
Route::patch('agendamentos/{id}/status', [AgendamentoController::class, 'updateStatus']);
