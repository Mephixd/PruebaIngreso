<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class personas extends Model
{
    use HasFactory;

    protected $table = "personas";
    protected $fillable = ["rut","razon_social", "actividades"];
    protected $casts = ['actividades' => 'array'];
    public $timestamps = false;
    protected $primaryKey  = 'rut';
    protected $keyType = 'string';

}
