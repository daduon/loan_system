<?php

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
        Schema::create('borrow_masters', function (Blueprint $table) {
            $table->string('id');
            $table->string('customer_id');
            $table->integer('coemployee_id');
            $table->string('borrowingtypecode', 255);
            $table->string('currencycode', 255);
            $table->decimal('loanamount', 10, 2);
            $table->date('startDate');
            $table->date('maturitydate');
            $table->integer('ispaid');
            $table->float('numofmonth');
            $table->String('payType', 10);
            $table->text('remarkdesc')->nullable(true);
            $table->decimal('applyinterestrate');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrow__masters');
    }
};
