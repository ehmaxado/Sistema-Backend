<?php

namespace Dev2\Models;

use Illuminate\Database\Eloquent\Model;

class Servico extends Model
{
    protected $table = 'servicos';

    protected $fillable = [
        'nome',
        'descricao',
        'duracao_minutos',
        'valor',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
        'valor' => 'decimal:2',
        'duracao_minutos' => 'integer',
    ];
}
