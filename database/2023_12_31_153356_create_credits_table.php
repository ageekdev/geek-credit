<?php

use Ageekdev\GeekCredit\Models\Credit;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $creditTable = $this->table();

        Schema::create($creditTable, function (Blueprint $table) {
            $table->id();
            $table->morphs('holder');
            $table->unsignedDecimal('initial_balance', 64, 8)
                ->default(0);
            $table->unsignedDecimal('remaining_balance', 64, 8)
                ->default(0);
            $table->boolean('can_expire')
                ->index();
            $table->dateTime('expires_at')
                ->nullable()
                ->index();
            $table->json('meta')
                ->nullable();
            $table->timestamps();
        });

        DB::statement(
            <<<SQL
            ALTER TABLE {$creditTable}
            ADD COLUMN has_remaining_balance BOOLEAN GENERATED ALWAYS AS (remaining_balance > 0);
            SQL
        );

        DB::statement(
            <<<SQL
            ALTER TABLE {$creditTable}
            ADD COLUMN unique_non_expire_holder VARCHAR(255) GENERATED ALWAYS AS (
                CASE
                    WHEN can_expire = 0 THEN MD5(CONCAT(holder_type, '::', holder_id))
                    ELSE NULL
                END
            ) STORED;
            SQL
        );

        Schema::table($creditTable, function (Blueprint $table) {
            $table->index('has_remaining_balance');
            $table->unique('unique_non_expire_holder');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credits');
    }

    public function table(): string
    {
        return (new Credit())->getTable();
    }
};
