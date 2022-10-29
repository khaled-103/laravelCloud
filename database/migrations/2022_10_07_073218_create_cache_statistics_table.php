<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cache_statistics', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('number_of_items');
            $table->unsignedInteger('total_items_size');
            $table->unsignedBigInteger('count_requests');
            $table->unsignedFloat('miss_rate');
            $table->unsignedFloat('hit_rate');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cache_statistics');
    }
};
