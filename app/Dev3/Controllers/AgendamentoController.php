<?php

namespace Dev3\Controllers;

use App\Http\Controllers\Controller;
use Dev3\Models\Agendamento;
use Dev3\Requests\AgendamentoRequest;
use Dev3\Services\AgendamentoService;
use Illuminate\Http\Request;

class AgendamentoController extends Controller
{
    public function __construct(private AgendamentoService $service) {}

    public function index(Request $request)
    {
        $query = Agendamento::with(['cliente', 'servico']);

        if ($request->filled('data')) {
            $query->whereDate('data', $request->data);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return response()->json(['data' => $query->orderBy('data')->orderBy('hora')->get()]);
    }

    public function store(AgendamentoRequest $request)
    {
        $agendamento = $this->service->criar($request->validated());
        return response()->json(['data' => $agendamento->load(['cliente', 'servico'])], 201);
    }

    public function show(int $id)
    {
        return response()->json(['data' => Agendamento::with(['cliente', 'servico'])->findOrFail($id)]);
    }

    public function updateStatus(Request $request, int $id)
    {
        $request->validate(['status' => ['required', 'string']]);
        $agendamento = $this->service->atualizarStatus($id, $request->status);
        return response()->json(['data' => $agendamento]);
    }

    public function destroy(int $id)
    {
        $agendamento = Agendamento::findOrFail($id);
        $agendamento->status = 'cancelado';
        $agendamento->save();
        return response()->json(['data' => ['id' => $id, 'status' => 'cancelado']]);
    }
}
