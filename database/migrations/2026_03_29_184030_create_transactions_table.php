<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            // account_id የነበረውን ወደ account_number ቀይረነዋል
            $table->string('account_number');
            $table->string('type'); // 'Deposit', 'Withdraw', 'Transfer Out', 'Transfer In'
            $table->decimal('amount', 15, 2);
            // ለታሪክ ገጹ የሚጠቅም መግለጫ (ለምሳሌ፡ Sent to...)
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('transactions');
    }
};
