<?php

namespace Dev2\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServicoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome' => ['required', 'string', 'max:150'],
            'descricao' => ['nullable', 'string'],
            'duracao_minutos' => ['required', 'integer', 'min:1'],
            'valor' => ['required', 'numeric', 'min:0'],
            'status' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'O nome do serviço é obrigatório.',
            'nome.max' => 'O nome não pode ter mais de 150 caracteres.',
            'duracao_minutos.required' => 'A duração do serviço é obrigatória.',
            'duracao_minutos.min' => 'A duração deve ser maior que zero.',
            'valor.required' => 'O valor do serviço é obrigatório.',
            'valor.min' => 'O valor não pode ser negativo.',
            'status.boolean' => 'O status do serviço deve ser verdadeiro ou falso.',
        ];
    }
}
