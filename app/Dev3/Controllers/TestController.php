<?php

namespace Dev3\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Dev3\Models\Agendamento;

class TestController extends Controller
{
    public function test(Request $request)
    {
        // Log para debug
        error_log('Request method: ' . $request->method());
        error_log('Request body: ' . $request->getContent());
        error_log('Request all: ' . json_encode($request->all()));
        error_log('Request json: ' . json_encode($request->json()->all()));
        
        return response()->json([
            'method' => $request->method(),
            'content_type' => $request->header('Content-Type'),
            'all' => $request->all(),
            'json' => $request->json()->all(),
            'body' => $request->getContent(),
        ]);
    }
}
