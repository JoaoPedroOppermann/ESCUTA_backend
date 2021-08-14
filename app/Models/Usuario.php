<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    use HasFactory;
    protected $fillable=['nome', 'sobrenome', 'email', 'data_nascimento', 'ativo', 'genero', 'biografia', 'termos', 'telefone', 'foto' ];
}

