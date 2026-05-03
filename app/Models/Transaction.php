<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model {
    use HasFactory;

    protected $fillable = [
        'account_number',
        'type',
        'amount',
        'reference_number', // አዲስ የተጨመረ
        'description',
        'staff_id'          // አዲስ የተጨመረ
    ];

    /**
     * የገንዘብ መጠኑ ሁልጊዜ በሁለት ዴሲማል እንዲመጣ ያደርጋል
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    /**
     * ይህ ዝውውር የማን እንደሆነ ለማወቅ (Inverse Relationship)
     */
    public function account() {
        // 'account_number' በሁለቱም ቴብል ላይ መኖሩን ያረጋግጣል
        return $this->belongsTo(Account::class, 'account_number', 'account_number');
    }

    /**
     * ዝውውሩን የፈጸመውን ሰራተኛ ለማወቅ (ለወደፊት Staff ቴብል ሲኖርህ)
     */
    public function staff() {
        return $this->belongsTo(User::class, 'staff_id');
    }
}
