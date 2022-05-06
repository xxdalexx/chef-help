<?php

use App\Models\Ingredient;
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
        Schema::create('cross_conversions', function (Blueprint $table) {
            $table->id();
            $table->morphs('ingredient');
            $table->string('quantity_one');
            $table->string('unit_one');
            $table->string('quantity_two');
            $table->string('unit_two');
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
        Schema::dropIfExists('cross_conversions');
    }
};
