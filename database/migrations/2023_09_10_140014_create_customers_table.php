<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function PHPSTORM_META\type;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id()->type('string');
            $table->integer('customer_type_id');
            $table->string('customer_name');
            $table->string('customer_email')->nullable();
            $table->string('customer_gender');
            $table->text('customer_picture')->nullable();
            $table->date('customer_dob');
            $table->string('customer_address');
            $table->string('customer_phone');
            $table->integer('isdiscount')->default(0)->comment('0');
            $table->tinyInteger('customer_status')->default(1)->comment('1=active,0=deleted');
            $table->string('customer_identity');
            $table->integer('customer_created_by')->nullable();
            $table->integer('customer_updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
