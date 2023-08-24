<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dogs', function (Blueprint $table) {
        // Basic Information
        $table->id();
        $table->string('name');
        $table->date('birth_date')->nullable();
        $table->string('color')->nullable();
        
        // Owner Information
        $table->uuid('user_id')->nullable(); 
        $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');

        // Medical Information
        $table->date('last_vaccination_date')->nullable();
        $table->string('veterinarian_name')->nullable();
        $table->text('medical_notes')->nullable();
        
        // Physical Characteristics
        $table->string('size')->nullable(); // e.g. small, medium, large
        $table->float('weight', 8, 2)->nullable(); // in kilograms or pounds, based on preference
        $table->string('coat_type')->nullable(); // e.g. short, long, curly
        
        // Behavioral Traits
        $table->boolean('is_trained')->default(false); // Whether the dog has received training or not
        $table->text('behavioral_notes')->nullable(); // Any notes on behavior, likes, dislikes, etc.
        
        // Timestamps
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
        Schema::dropIfExists('dogs');
    }
}