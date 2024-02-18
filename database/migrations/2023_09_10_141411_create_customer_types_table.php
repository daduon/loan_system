<?php

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
        Schema::create('customer_types', function (Blueprint $table) {
            $table->id();
            $table->string('customer_type_name');
            $table->string('customer_type_des')->nullable();
            $table->decimal('customer_type_rate');
            $table->integer('customer_type_status')->default(1)->comment('1=active,0=deleted');
            $table->integer('customer_type_created_by')->nullable();
            $table->integer('customer_type_updated_by')->nullable();
            $table->timestamps();
        });

        DB::table('customer_types')->insert([
            [
                'customer_type_name' => 'General',
                'customer_type_rate' => 0,
                'created_at' => now(),
                'updated_at' => now()

            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_types');
    }
};
