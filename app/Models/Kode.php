<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kode extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function kodeToSubKode(){
        return $this->hasMany(SubKode::class, 'id_kode', 'id');
    }
}
