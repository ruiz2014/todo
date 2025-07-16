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
        Schema::create('belong_locals', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('company_id')->default(1); 
            $table->unsignedInteger('establishments_id');
            $table->unsignedInteger('local_id');
            $table->unsignedInteger('user_id');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('belong_locals');
    }
};
