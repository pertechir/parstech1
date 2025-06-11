<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessUserTable extends Migration
{
    public function up()
    {
        Schema::create('business_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('tenant_id'); // id ستون مدل Tenant در stancl tenancy string است
            $table->timestamps();

            $table->unique(['user_id', 'tenant_id']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('business_user');
    }
}
