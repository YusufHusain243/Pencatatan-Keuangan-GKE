<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubSubKode extends Model
{
    use HasFactory;

    protected $guarded = ["id"];

    public function subSubKodeToSubKode()
    {
        return $this->belongsTo(SubKode::class, 'id_sub_kode', 'id');
    }

    public function subSubKodeToDana()
    {
        return $this->hasMany(Dana::class, 'id_sub_sub_kode', 'id');
    }
}
