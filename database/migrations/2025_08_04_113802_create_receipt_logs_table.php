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
        Schema::create('receipt_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('company_id')->default(1);
            $table->unsignedInteger('local_id');
            $table->unsignedInteger('user_id');
            $table->string('receipt_code', 30)->nullable();
            $table->string('identifier', 30)->nullable();
            $table->string('ticket', 50)->nullable();
            $table->unsignedTinyInteger('receipt_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipt_logs');
    }
};
