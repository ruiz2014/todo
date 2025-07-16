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
        Schema::create('transfer_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('company_id')->default(1);
            $table->unsignedInteger('warehouse_id');
            $table->unsignedTinyInteger('local_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('product_id');
            $table->decimal('output', 8,2)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfer_products');
    }
};
