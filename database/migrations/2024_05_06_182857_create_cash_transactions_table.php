<?php

use App\Enums\StatusCode;
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
        Schema::create('cash_transactions', function (Blueprint $table) {
            $table->id();
            $table->decimal('cash_total_usd', 20, 2)->unsigned()->default(0);
            $table->bigInteger('cash_total_kh')->unsigned()->default(0);
            $table->string('date');
            $table->string('status', 2)->default(StatusCode::ACTIVE->value);
            $table->text('cash_in_desc');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_transactions');
    }
};
