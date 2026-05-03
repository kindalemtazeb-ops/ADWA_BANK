<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    /**
     * በአንድ ጊዜ (Mass Assignment) እንዲሞሉ የሚፈቀድላቸው የዳታቤዝ ረድፎች
     */
    protected $fillable = [
        'full_name',
        'phone_number',
        'balance',
        'account_number',
        'pin',
        'photo',
    ];

    /**
     * የዳታ አይነቶችን በራስ-ሰር ለመቀየር (Casting)
     */
    protected $casts = [
        'pin' => 'hashed',            // ፒን ቁጥር ተደብቆ (Hashed) እንዲቀመጥ
        'balance' => 'decimal:2',     // የብር መጠን በሁለት ዴሲማል እንዲቀመጥ
    ];

    /**
     * ከአንድ አካውንት ጋር የተያያዙ የገንዘብ ዝውውሮችን ለማግኘት
     * (One-to-Many Relationship)
     */
    public function transactions()
    {
        // አካውንቱን ከዝውውር ታሪኩ ጋር በአካውንት ቁጥር ያገናኛል
        return $this->hasMany(Transaction::class, 'account_number', 'account_number');
    }

    /**
     * ማሳሰቢያ፦ የ 'id' ረድፍ በዳታቤዝህ ውስጥ ስላለ (እንደ image_74fb5d.jpg)
     * Primary Key መግለጽ አያስፈልግም። ላራቬል በራሱ 'id'ን ይጠቀማል።
     */
}

