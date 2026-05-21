<?php

namespace Dev1\Controllers;

use App\Http\Controllers\Controller;
use Dev1\Models\Cliente;
use Dev1\Requests\ClienteRequest;

class ClienteController extends Controller
{
    public function index()
    {
        return response()->json([
            'data' => Cliente::orderBy('nome')->get(),
        ]);
    }

    public function store(ClienteRequest $request)
    {
        $cliente = Cliente::create($request->validated());

        return response()->json(['data' => $cliente], 201);
    }

    public function show(int $id)
    {
        return response()->json([
            'data' => Cliente::findOrFail($id),
        ]);
    }

    public function update(ClienteRequest $request, int $id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->update($request->validated());

        return response()->json(['data' => $cliente]);
    }

    public function destroy(int $id)
    {
        Cliente::findOrFail($id)->delete();

        return response()->json(['data' => ['id' => $id]]);
    }
}
