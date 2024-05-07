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
        Schema::create('cash_ins', function (Blueprint $table) {
            $table->id();
            $table->string('cash_in_user_id');
            $table->decimal('cash_in_amt_usd', 20, 2)->unsigned();
            $table->bigInteger('cash_in_amt_khr')->unsigned();
            $table->decimal('income_cash_in_usd', 20, 2)->unsigned();
            $table->bigInteger('income_cash_in_kh')->unsigned();
            $table->string('cash_in_date');
            $table->string('cash_in_status', 2)->default(StatusCode::ACTIVE->value);
            $table->text('cash_in_desc');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_ins');
    }
};
