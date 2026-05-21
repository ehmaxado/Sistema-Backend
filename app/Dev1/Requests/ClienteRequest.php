<?php

namespace Dev1\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome'       => ['required', 'string', 'max:150'],
            'email'      => ['nullable', 'email', 'max:150', Rule::unique('clientes', 'email')->ignore($this->route('cliente'))],
            'telefone'   => ['nullable', 'string', 'max:20'],
            'observacao' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'O nome do cliente é obrigatório.',
            'nome.max'      => 'O nome não pode ter mais de 150 caracteres.',
            'email.email'   => 'Informe um e-mail válido.',
            'email.unique'  => 'Este e-mail já está cadastrado.',
            'email.max'     => 'O e-mail não pode ter mais de 150 caracteres.',
            'telefone.max'  => 'O telefone não pode ter mais de 20 caracteres.',
        ];
    }
}
