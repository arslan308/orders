<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profits', function (Blueprint $table) {
            $table->increments('id');
            $table->string('client_id')->nullable();
            $table->string('month')->nullable();
            $table->text('items')->nullable();
            $table->string('cost')->nullable();
            $table->string('profit')->nullable(); 
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
        Schema::dropIfExists('profits');
    }
}
