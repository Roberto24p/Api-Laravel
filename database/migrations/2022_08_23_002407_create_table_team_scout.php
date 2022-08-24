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
        Schema::create('team_scout', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('scout_id');
            $table->unsignedBigInteger('team_id');

            $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
            $table->foreign('scout_id')->references('id')->on('scouts')->onDelete('cascade');
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
        Schema::dropIfExists('team_scout');
    }
};
