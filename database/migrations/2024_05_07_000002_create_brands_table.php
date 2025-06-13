<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('brands')) {
            Schema::create('brands', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('description')->nullable(); // این خط اضافه شد
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};
