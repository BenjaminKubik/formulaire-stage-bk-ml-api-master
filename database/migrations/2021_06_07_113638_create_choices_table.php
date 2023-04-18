<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('choices', function (Blueprint $table) {
            $table->bigInteger('num_question');
            $table->string('choice');
            $table->bigInteger('num_sec');
            $table->bigInteger('form_id');
            $table->primary(['form_id', 'num_sec', 'num_question', 'choice']);
            $table->timestamps();
            $table->foreign('form_id')->references('id')->on('forms')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('choices');
    }
}
