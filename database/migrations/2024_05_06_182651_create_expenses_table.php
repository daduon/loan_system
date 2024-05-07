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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('expense_no');
            $table->text('expense_desc');
            $table->string('expense_date');
            $table->string('expense_by');
            $table->string('expense_status', 2)->default(StatusCode::ACTIVE->value);
            $table->decimal('expense_amount_usd', 20, 2)->unsigned()->default(0);
            $table->bigInteger('expense_amount_kh')->unsigned()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
