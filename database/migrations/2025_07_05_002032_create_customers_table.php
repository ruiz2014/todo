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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('company_id')->nullable()->default(1);
            $table->unsignedInteger('local_id');
            $table->unsignedInteger('user_id');
            $table->string('name', 150);
            $table->string('tipo_doc', 10)->nullable();
            $table->string('document', 30)->nullable();
            $table->string('phone', 25)->nullable();
            $table->string('address', 250)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('ubigeo', 50)->nullable()->default('13001');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
