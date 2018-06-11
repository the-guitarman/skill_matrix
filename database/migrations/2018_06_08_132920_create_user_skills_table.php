<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserSkillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_skills', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table
                ->integer('user_id')
                ->unsigned();
            $table
                ->integer('skill_id')
                ->unsigned();
            $table->tinyInteger('grade');
            $table->timestampsTz();

            $table
                ->foreign('user_id')
                ->references('id')
                ->on('users');
            $table
                ->foreign('skill_id')
                ->references('id')
                ->on('skills');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_skills');
    }
}
