<?php

use App\Models\Ingredient;
use App\Models\Location;
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
        Schema::create('ingredient_location', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Ingredient::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Location::class)->constrained()->cascadeOnDelete();
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
        Schema::dropIfExists('ingredient_location');
    }
};
