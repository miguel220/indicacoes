<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Indicacao extends Model
{
    use HasFactory;

    protected $table = 'indicacoes';

    protected $fillable = ['nome', 'email', 'telefone'];

    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'atualizado_em';

}
