<?php

namespace Dev2\Models;

use Illuminate\Database\Eloquent\Model;

class Servico extends Model
{
    protected $table = 'servicos';
    protected $fillable = ['nome', 'descricao', 'preco', 'status'];
    protected $casts = [
        'preco' => 'decimal:2',
        'status' => 'boolean',
    ];
}
