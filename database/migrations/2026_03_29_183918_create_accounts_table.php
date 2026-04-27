<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('accounts', function (Blueprint $table) {
        $table->id();
        $table->string('full_name');
        $table->string('account_number', 13)->unique(); // string እና 13 length መሆኑን እርግጠኛ ሁን
        $table->string('phone_number')->unique();
        $table->string('pin')->unique();
        $table->decimal('balance', 15, 2)->default(0);
        $table->timestamps();
    });
}
    public function down(): void {
        Schema::dropIfExists('accounts');
    }
};
