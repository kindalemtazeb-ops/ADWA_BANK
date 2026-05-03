<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('account_number'); // የደንበኛው አካውንት
            $table->string('type'); // 'Deposit', 'Withdraw', 'Transfer Out', 'Transfer In'
            $table->decimal('amount', 15, 2);

            // --- ሪል ወርልድ ባንክ ላይ የሚጨመሩ ---
            // 'after' የሚለው እዚህ ጋር ተወግዷል
            $table->string('reference_number')->unique();

            // ከሌላ ባንክ ጋር ለወደፊት ለማገናኘት (Optional)
            $table->string('receiver_account')->nullable();

            $table->string('description')->nullable();

            // ዝውውሩን የፈጸመው ሰራተኛ መታወቂያ (ለኦዲት)
            $table->unsignedBigInteger('staff_id')->nullable();

            $table->timestamps();

            // ኢንዴክስ መጨመር ፍለጋን ያፈጥናል
            $table->index('account_number');
            $table->index('reference_number');
        });
    }

    public function down(): void {
        Schema::dropIfExists('transactions');
    }
};

