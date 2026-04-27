<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model {
    // በ Controller ውስጥ የምንጠቀማቸውን ፊልዶች እዚህ መፍቀድ አለብን
    protected $fillable = [
        'account_number', // የአካውንት ቁጥር
        'type',           // የግብይት ዓይነት (Deposit, Withdraw...)
        'amount',         // የገንዘብ መጠን
        'description'     // መግለጫ (ለምሳሌ፡ Sent to...)
    ];
}
