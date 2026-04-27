<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ተጠቃሚው ካለ ያድሰዋል፣ ከሌለ ደግሞ አዲስ ይፈጥራል
        User::updateOrCreate(
            ['email' => 'kindalemtazeb@gmail.com'], // ለመፈተሽ የሚጠቀምበት መረጃ
            [
                'name' => 'Kindalem Tazeb',
                'password' => Hash::make('Kita@2375'),
                'email_verified_at' => now(), // አስፈላጊ ከሆነ ኢሜይሉ የተረጋገጠ እንዲሆን
            ]
        );

        // ለሙከራ ያህል ሌሎች ተጠቃሚዎችን መጨመር ከፈለግክ ከታች ያለውን ማብራት ትችላለህ
        // User::factory(10)->create();
    }
}
