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
        Schema::create('io_cashes', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('local_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('cash_id');
            $table->unsignedTinyInteger('type');
            $table->double('io_amount', 8,2);
            $table->string('observation', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('io_cashes');
    }
};
