<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained('accounts')->onDelete('cascade');
            $table->string('type'); // 'Transfer', 'Deposit'
            $table->decimal('amount', 15, 2);
            $table->string('receiver_phone')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('transactions');
    }
};
