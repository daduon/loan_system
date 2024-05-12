<?php

use App\Enums\CashTransactionType;
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
            $table->decimal('cash_in_amt_usd', 20, 2)->unsigned()->nullable()->default(0);
            $table->bigInteger('cash_in_amt_khr')->unsigned()->nullable()->default(0);
            $table->decimal('income_cash_in_usd', 20, 2)->unsigned()->nullable()->default(0);
            $table->bigInteger('income_cash_in_kh')->unsigned()->nullable()->default(0);
            $table->string('cash_in_date');
            $table->string('cash_in_status', 2)->default(StatusCode::ACTIVE->value);
            $table->text('cash_in_desc')->nullable()->default(CashTransactionType::LOAN->value);
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
