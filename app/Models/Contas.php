<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contas extends Model
{
    use HasFactory;
    protected $fillable = ['nome_conta', 'valor_pago_mensal', 'data_inicio_conta', 'dia_vencimento'];
}
