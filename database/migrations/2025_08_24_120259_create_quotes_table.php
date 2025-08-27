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
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('company_id')->default(1);
            $table->unsignedInteger('local_id');
            $table->unsignedInteger('customer_id');
            $table->string('document_code', 17)->nullable();
            $table->string('reference_document', 17)->nullable();
            $table->unsignedTinyInteger('currency')->default(1);
            $table->double('total', 8,2);
            $table->unsignedInteger('seller');
            $table->smallInteger('serie')->default(1);
            $table->string('identifier', 20)->nullable();
            $table->unsignedInteger('numeration')->nullable();
            $table->string('message', 70)->nullable();
            $table->unsignedTinyInteger('status')->nullable()->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotes');
    }
};
