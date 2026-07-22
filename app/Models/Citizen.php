<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laragear\Rut\HasRut;

class Citizen extends Model
{
    use HasRut, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'rut_num',
        'rut_vd',
        'rut_type',
    ];

    protected $dates = [
        'deleted_at',
    ];
}
