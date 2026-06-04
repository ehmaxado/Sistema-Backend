<?php

namespace Dev3\Models;

use Illuminate\Database\Eloquent\Model;

class Agendamento extends Model
{
    protected $table = 'agendamentos';
    protected $fillable = ['cliente_id', 'servico_id', 'data', 'hora', 'status', 'observacao'];
    protected $casts = [
        'data' => 'date:Y-m-d',
        'hora' => 'string',
    ];

    public function cliente()
    {
        return $this->belongsTo(\Dev1\Models\Cliente::class, 'cliente_id');
    }

    public function servico()
    {
        return $this->belongsTo(\Dev2\Models\Servico::class, 'servico_id');
    }
}
