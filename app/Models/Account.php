<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    // እነዚህ በሥዕል 2 ላይ የነበሩት ፊልዶች መሆናቸውን አረጋግጥ
    protected $fillable = [
        'full_name',
        'phone_number',
        'balance',
        'account_number',
        'secret_number',
        'pin',
    ];
}
