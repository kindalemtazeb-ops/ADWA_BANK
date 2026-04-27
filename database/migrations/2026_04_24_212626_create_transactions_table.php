<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('transactions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('account_id')->constrained('accounts')->onDelete('cascade');
        $table->string('type'); // 'Deposit', 'Withdraw', 'Transfer'
        $table->decimal('amount', 15, 2);
        $table->decimal('balance_after', 15, 2); // ከግብይቱ በኋላ የነበረ ቀሪ ሂሳብ
        $table->string('description')->nullable(); // ለምሳሌ፡ "ለአበበ የተላከ"
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
