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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('company_id')->default(1); 
            $table->unsignedInteger('user_id');
            $table->unsignedTinyInteger('local_id');
            $table->unsignedTinyInteger('from_role_id');
            $table->unsignedTinyInteger('to_role_id')->nullable();
            $table->string('title', 100);
            $table->tinyText('notes');
            $table->unsignedTinyInteger('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
