<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubKode extends Model
{
    use HasFactory;

    protected $guarded = ["id"];

    public function subKodeToKode()
    {
        return $this->belongsTo(Kode::class, 'id_kode', 'id');
    }

    public function subKodeToSubSubKode()
    {
        return $this->hasMany(SubSubKode::class, 'id_sub_kode', 'id');
    }

    public function subKodeToDana()
    {
        return $this->hasMany(Dana::class, 'id_sub_kode', 'id');
    }
}
