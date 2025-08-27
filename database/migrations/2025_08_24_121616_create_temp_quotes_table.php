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
        Schema::create('temp_quotes', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('company_id')->default(1);
            $table->unsignedInteger('local_id');
            $table->unsignedInteger('user_id');
            $table->string('code', 20);
            $table->unsignedInteger('customer_id')->nullable();
            $table->unsignedInteger('product_id');
            $table->double('price', 8,2);
            $table->decimal('amount', 8, 2);
            $table->text('note')->nullable();
            $table->unsignedTinyInteger('status')->nullable()->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temp_quotes');
    }
};
