<?php

namespace Dev2\Controllers;

use App\Http\Controllers\Controller;
use Dev2\Models\Servico;
use Dev2\Requests\ServicoRequest;

class ServicoController extends Controller
{
    public function index()
    {
        return response()->json([
            'data' => Servico::orderBy('nome')->get(),
        ]);
    }

    public function store(ServicoRequest $request)
    {
        $servico = Servico::create($request->validated());

        return response()->json(['data' => $servico], 201);
    }

    public function show(int $id)
    {
        return response()->json([
            'data' => Servico::findOrFail($id),
        ]);
    }

    public function update(ServicoRequest $request, int $id)
    {
        $servico = Servico::findOrFail($id);
        $servico->update($request->validated());

        return response()->json(['data' => $servico]);
    }

    public function destroy(int $id)
    {
        Servico::findOrFail($id)->delete();

        return response()->json(['data' => ['id' => $id]]);
    }

    public function toggleStatus(int $id)
    {
        $servico = Servico::findOrFail($id);
        $servico->status = ! $servico->status;
        $servico->save();

        return response()->json([
            'data' => [
                'id' => $servico->id,
                'status' => $servico->status,
            ],
        ]);
    }
}
