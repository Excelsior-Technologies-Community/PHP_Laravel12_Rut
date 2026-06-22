<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laragear\Rut\HasRut;

class Citizen extends Model
{
    use HasRut;

    protected $fillable = [
        'name',
        'email',
        'rut_num',
        'rut_vd',
    ];
}
