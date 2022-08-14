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
        Schema::create('persons', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('last_name');
            $table->string('dni');
            $table->date('born_date');
            $table->enum('type_blood', ['+O', '-O', '+A', '-A', '+B', '-B', '-AB', '+AB']);
            $table->string('phone');
            $table->enum('gender', ['1', '0']);
            $table->string('image')->nullable();
            $table->string('nacionality');
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
        Schema::dropIfExists('persons');
    }
};
