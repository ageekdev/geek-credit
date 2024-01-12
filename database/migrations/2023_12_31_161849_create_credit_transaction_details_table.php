<?php

use Ageekdev\GeekCredit\Models\Credit;
use Ageekdev\GeekCredit\Models\CreditTransaction;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('credit_transaction_details', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(CreditTransaction::class)->constrained();
            $table->foreignIdFor(Credit::class)->constrained();
            $table->unsignedDecimal('amount', 64, 8);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_transaction_details');
    }
};
