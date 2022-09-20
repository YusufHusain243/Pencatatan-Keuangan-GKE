<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailBank extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function detailBankToBank()
    {
        return $this->belongsTo(AkunBank::class, 'id_bank', 'id');
    }

    public function detailBankToDana()
    {
        return $this->belongsTo(Dana::class, 'id_dana', 'id');
    }
}
