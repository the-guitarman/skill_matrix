<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSkillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('skills', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table
                ->integer('skill_group_id')
                ->unsigned();
            $table->string('name');
            $table->timestampsTz();
            $table->softDeletesTz();

            $table
                ->foreign('skill_group_id')
                ->references('id')
                ->on('skill_groups')
                ->onDelete('cascade');
            $table->unique('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('skills');
    }
}
