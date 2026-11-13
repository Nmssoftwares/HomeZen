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
            $table->string('company_name');
            $table->string('company_code', 10)->nullable();
            $table->string('company_address');
            $table->string('gst_number', 50)->nullable();
            $table->string('pan_number', 50)->nullable();
            $table->string('company_logo')->nullable();
            $table->string('company_email')->unique()->nullable();
            $table->string('company_phone')->unique()->nullable();
            $table->string('company_website')->nullable();
            $table->string('timezone', 50)->default('Asia/Kolkata');
            $table->string('date_format', 20)->default('d-m-Y');
            $table->tinyInteger('status')->default(1)->comment('1=Active, 0=Inactive');
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
