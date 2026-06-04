<?php

namespace Dev3\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AgendamentoRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'cliente_id'  => ['required', 'integer', 'exists:clientes,id'],
            'servico_id'  => ['required', 'integer', 'exists:servicos,id'],
            'data'        => ['required', 'date_format:Y-m-d'],
            'hora'        => ['required', 'date_format:H:i'],
            'observacao'  => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'cliente_id.exists' => 'Cliente não encontrado.',
            'servico_id.exists' => 'Serviço não encontrado.',
            'data.required'     => 'A data é obrigatória.',
            'hora.required'     => 'A hora é obrigatória.',
        ];
    }
}
