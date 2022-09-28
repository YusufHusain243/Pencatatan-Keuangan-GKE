<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dana extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function danaToDetailBank()
    {
        return $this->hasOne(DetailBank::class, 'id_dana', 'id');
    }

    public function danaToKode()
    {
        return $this->belongsTo(Kode::class, 'id_kode', 'id');
    }

    public function danaToSubKode()
    {
        return $this->belongsTo(SubKode::class, 'id_sub_kode', 'id');
    }
    
    public function danaToSubSubKode()
    {
        return $this->belongsTo(SubSubKode::class, 'id_sub_sub_kode', 'id');
    }
}
