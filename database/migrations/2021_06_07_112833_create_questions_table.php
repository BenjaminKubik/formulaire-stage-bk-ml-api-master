<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->integer('num_question');
            $table->bigInteger('form_id');
            $table->integer('num_sec');
            $table->string('libelle');
            $table->string('type');
            $table->float('min')->nullable();
            $table->float('max')->nullable();
            $table->float('pas')->nullable();
            $table->foreign('form_id')->references('id')->on('forms')
                ->onDelete('cascade');
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
        Schema::dropIfExists('questions');
    }
}
