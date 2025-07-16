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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('company_name', 100)->nullable();
            $table->string('document', 20);
            $table->string('address', 200);
            $table->string('ubigeo', 10)->nullable();
            $table->unsignedTinyInteger('sector_id')->default(1);
            $table->unsignedTinyInteger('number_employees')->default(1);
            $table->unsignedTinyInteger('number_subsidiary')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
