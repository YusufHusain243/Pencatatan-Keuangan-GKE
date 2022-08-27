<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AkunBank extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function bankToDetailBank(){
        return $this->hasMany(DetailBank::class, 'id_bank', 'id');
    }
}
