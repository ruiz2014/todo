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
        Schema::create('providers', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('company_id')->default(1);
            $table->unsignedInteger('user_id');
            $table->string('name', 150); 
            $table->string('ruc', 14)->nullable(); 
            $table->string('address', 240)->nullable(); 
            $table->string('phone', 20)->nullable(); 
            $table->string('email', 30)->nullable(); 
            $table->string('agent', 100)->nullable();
            $table->string('agent_phone', 20)->nullable(); 
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('providers');
    }
};
