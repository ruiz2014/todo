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
        Schema::create('buy_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('company_id')->default(1);
            $table->unsignedInteger('user_id');
            $table->string('document', 50)->nullable();
            $table->string('code', 20);
            $table->decimal('total', 10,2)->default(0);
            $table->string('batch', 50)->nullable();
            $table->decimal('stock', 8,2)->default(0);
            $table->string('type_payment', 50)->nullable();
            // $table->unsignedTinyInteger('type_payment');
            $table->unsignedTinyInteger('location_type');
            $table->unsignedInteger('location_id');
            $table->unsignedInteger('provider_id')->default(1);
            $table->string('notation', 249)->nullable();
            $table->unsignedTinyInteger('status')->default(0);
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buy_products');
    }
};
