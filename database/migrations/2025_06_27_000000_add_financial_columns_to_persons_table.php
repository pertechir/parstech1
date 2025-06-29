<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFinancialColumnsToPersonsTable extends Migration
{
    public function up()
    {
        Schema::table('persons', function (Blueprint $table) {
            // چک کردن وجود ستون‌ها قبل از اضافه کردن
            if (!Schema::hasColumn('persons', 'total_purchases')) {
                $table->decimal('total_purchases', 20, 2)->default(0);
            }
            if (!Schema::hasColumn('persons', 'total_sales')) {
                $table->decimal('total_sales', 20, 2)->default(0);
            }
            if (!Schema::hasColumn('persons', 'balance')) {
                $table->decimal('balance', 20, 2)->default(0);
            }
            if (!Schema::hasColumn('persons', 'status')) {
                $table->string('status')->default('active');
            }
            if (!Schema::hasColumn('persons', 'last_transaction_at')) {
                $table->timestamp('last_transaction_at')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('persons', function (Blueprint $table) {
            $table->dropColumn([
                'total_purchases',
                'total_sales',
                'balance',
                'status',
                'last_transaction_at'
            ]);
        });
    }
}
