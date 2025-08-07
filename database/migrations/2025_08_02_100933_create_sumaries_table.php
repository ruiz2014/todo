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
        Schema::create('sumaries', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('company_id')->default(1);
            $table->unsignedInteger('local_id');
            $table->unsignedInteger('user_id');
            $table->string('identifier', 30)->nullable();
            $table->string('ticket', 50)->nullable();
            $table->string('cdr', 5)->nullable();
            $table->string('status', 50)->nullable();
            $table->string('hash', 50)->nullable();
            $table->string('message', 70)->nullable();
            $table->date('date_created');
            $table->date('date_send');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sumaries');
    }
};
