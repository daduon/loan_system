<?php

use Illuminate\Database\DBAL\TimestampType;
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
        Schema::create('borrow_schedules', function (Blueprint $table) {
            $table->string('id');
            $table->String('borrowing_id');
            $table->string('paymentapplydate', 255);
            $table->string('taxamount', 255);
            $table->decimal('transactioninterestamount', 10, 2);
            $table->decimal('transactionprincipal', 10, 2);
            $table->date('paymentfromdate');
            $table->date('paymenttodate');
            $table->integer('ledgerstatuscode');
            $table->integer('schedulestatuscode');
            $table->decimal('applyinterestrate');
            $table->decimal('repaytaxamount', 10, 2);
            $table->decimal('repayinterest',10, 2);
            $table->integer('scheduleseqno');
            $table->string('calc');
            $table->decimal('repayprincipal',10,2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrow__schedules');
    }
};
