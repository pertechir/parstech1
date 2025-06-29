<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_shareholder', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('service_id');
            $table->unsignedBigInteger('shareholder_id');
            $table->decimal('percent', 6, 2)->nullable(); // درصد سهم هر سهامدار از خدمت
            $table->timestamps();

            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
            $table->foreign('shareholder_id')->references('id')->on('shareholders')->onDelete('cascade');
            $table->unique(['service_id', 'shareholder_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_shareholder');
    }
};
