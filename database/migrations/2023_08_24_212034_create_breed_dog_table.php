<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBreedDogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('breed_dog', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dog_id');
            $table->unsignedBigInteger('breed_id');
            $table->timestamps();

            // Set foreign keys
            $table->foreign('dog_id')->references('id')->on('dogs')->onDelete('cascade');
            $table->foreign('breed_id')->references('id')->on('breeds')->onDelete('cascade');

            // Unique constraint so that a specific breed cannot be associated with the same dog more than once
            $table->unique(['dog_id', 'breed_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('breed_dog');
    }
}