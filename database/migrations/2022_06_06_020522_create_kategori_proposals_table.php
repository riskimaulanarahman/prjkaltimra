<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKategoriProposalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kategori_proposals', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori')->nullable();
            $table->string('keterangan_kategori')->nullable();
            $table->string('waktu_minimum')->nullable();
            $table->string('batas_approval')->nullable(); // ALTER TABLE `kategori_proposals` ADD `batas_approval` VARCHAR(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL AFTER `waktu_minimum`;
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
        Schema::dropIfExists('kategori_proposals');
    }
}
