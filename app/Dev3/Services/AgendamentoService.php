<?php

namespace Dev3\Services;

use Dev3\Models\Agendamento;
use Dev2\Models\Servico;
use Illuminate\Validation\ValidationException;

class AgendamentoService
{
    public function criar(array $dados): Agendamento
    {
        $servico = Servico::findOrFail($dados['servico_id']);

        if (!$servico->status) {
            throw ValidationException::withMessages([
                'servico_id' => 'Não é possível agendar um serviço inativo.',
            ]);
        }

        $conflito = Agendamento::where('data', $dados['data'])
            ->where('hora', $dados['hora'])
            ->exists();

        if ($conflito) {
            throw ValidationException::withMessages([
                'data' => 'Já existe um agendamento neste dia e horário.',
            ]);
        }

        return Agendamento::create(array_merge($dados, ['status' => 'agendado']));
    }

    public function atualizarStatus(int $id, string $novoStatus): Agendamento
    {
        $statusPermitidos = ['agendado', 'realizado', 'cancelado'];

        if (!in_array($novoStatus, $statusPermitidos)) {
            throw ValidationException::withMessages([
                'status' => 'Status inválido. Use: agendado, realizado ou cancelado.',
            ]);
        }

        $agendamento = Agendamento::findOrFail($id);
        $agendamento->status = $novoStatus;
        $agendamento->save();

        return $agendamento;
    }
}
