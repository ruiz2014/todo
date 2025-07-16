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
        Schema::create('warehouse_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('company_id')->default(1); 
            $table->unsignedInteger('warehouse_id');
            $table->unsignedTinyInteger('local_id')->nullable();
            $table->unsignedInteger('product_id');
            $table->string('batch', 50)->nullable();
            $table->decimal('entry', 8,2)->default(0);
            $table->decimal('output', 8,2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_logs');
    }
};
