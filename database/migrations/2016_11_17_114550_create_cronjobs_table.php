<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCronjobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cronjobs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('grace');
            $table->string('grace_units');
            $table->integer('period');
            $table->string('period_units');
            $table->unsignedInteger('user_id');
            $table->string('uuid');
            $table->string('email')->nullable();
            $table->datetime('last_run')->nullable();
            $table->boolean('is_silenced')->default(false);
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
        Schema::dropIfExists('cronjobs');
    }
}
