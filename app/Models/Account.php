<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'phone_number',
        'account_number',
        'balance',
        'pin',
        'interest_rate', // አዲሱ እዚህ ጋር ተጨምሯል
    ];
}
