<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn(['plan_name', 'courses_allowed', 'price']);
        });
    }

    public function down()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->string('plan_name')->nullable();
            $table->integer('courses_allowed')->default(0);
            $table->decimal('price', 8, 2)->default(0.00);
        });
    }
};
