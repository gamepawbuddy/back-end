<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersActivityTable extends Migration
{
    public function up()
    {
        Schema::create('user_activity', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('user_id');
            $table->string('activity_type', 20);
            $table->dateTime('activity_time');
            $table->text('activity_details')->nullable();
        
            // Polymorphic columns
            $table->unsignedBigInteger('performed_by_id');
            $table->string('performed_by_type');
        
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
        
    }
    
    public function down()
    {
        Schema::dropIfExists('user_activity');
    }
}