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
        Schema::create('temp_buys', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('company_id')->nullable()->default(1);
            $table->unsignedInteger('user_id');
            $table->string('code', 20);
            $table->unsignedInteger('product_id');
            $table->double('cost', 8,2);
            $table->decimal('stock', 8, 2);
            $table->unsignedTinyInteger('status')->nullable()->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temp_buys');
    }
};
