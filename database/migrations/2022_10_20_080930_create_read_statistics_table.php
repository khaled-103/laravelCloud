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
        Schema::create('read_statistics', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('number_of_items');
            $table->unsignedInteger('total_items_size');
            $table->string('policy');
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
        Schema::dropIfExists('read_statistics');
    }
};
