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
        Schema::create('set_up_companies', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('company_id')->default(1);
            $table->unsignedInteger('user_id');
            $table->unsignedTinyInteger('redirect_after')->default(0);
            $table->unsignedTinyInteger('uploaded_products')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('set_up_companies');
    }
};
