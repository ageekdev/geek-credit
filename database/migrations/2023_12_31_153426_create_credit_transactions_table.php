<?php

use Ageekdev\GeekCredit\Enums\CreditTransactionType;
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
        Schema::create('credit_transactions', function (Blueprint $table) {
            $table->id();
            $table->morphs('holder');
            $table->unsignedDecimal('amount', 64, 8);
            $table->tinyInteger('type')
                // ->comment(
                //     collect(
                //         CreditTransactionType::cases(),
                //     )->map(fn ($value, $key) => "{$key}: {$value->text()}")->join(', ')
                // )
                ->index();
            $table->string('name')
                ->nullable()
                ->index();
            $table->string('description')
                ->nullable();
            $table->json('meta')
                ->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_transactions');
    }
};
