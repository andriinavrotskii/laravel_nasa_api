<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNeosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('neos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('reference');
            $table->date('date');
            $table->string('name');
            $table->float('speed', 20, 10);
            $table->boolean('is_hazardous');
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
        Schema::dropIfExists('neos');
    }
}
