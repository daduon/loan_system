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
        Schema::create('brances', function (Blueprint $table) {
            $table->increments('branch_id');
            $table->string('branch_name'); 
            $table->string('branch_location'); 
            $table->string('branch_phone'); 
            $table->string('branch_des') ->nullable(); 
            $table->string('branch_email')->nullable(); 
            $table->string('branch_wifi_password')->nullable(); 
            $table->timestamps();
        });
    }
   
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brances');
    }
};
