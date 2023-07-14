<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdditionalProposalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('additional_proposals', function (Blueprint $table) {
            $table->id();
            $table->integer('id_proposal')->nullable();
            $table->integer('id_lpj')->nullable();
            $table->string('kpb_1')->nullable();
            $table->string('kpb_2')->nullable();
            $table->string('kpb_3')->nullable();
            $table->string('kpb_4')->nullable();
            $table->string('psl')->nullable();
            $table->string('psr')->nullable();
            $table->string('go')->nullable();
            $table->string('lr')->nullable();
            $table->string('jasa')->nullable();
            $table->string('part')->nullable();
            $table->string('oli')->nullable();
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
        Schema::dropIfExists('additional_proposals');
    }
}
