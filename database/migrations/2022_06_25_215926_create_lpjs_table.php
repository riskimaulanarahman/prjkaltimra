<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLpjsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lpjs', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->integer('id_proposal');
            $table->string('tempat_lpj')->nullable();
            $table->string('periode_start_lpj')->nullable();
            $table->string('periode_end_lpj')->nullable();
            $table->string('lokasi_lpj')->nullable();
            $table->string('unit_lpj')->nullable();
            $table->text('finance_lpj')->nullable();
            $table->string('target_database_lpj')->nullable();
            $table->string('target_penjualan_lpj')->nullable();
            $table->string('target_prospectus_lpj')->nullable();
            $table->string('target_downloader_lpj')->nullable();
            $table->text('dana_lpj')->nullable(); // [item, beban dealer, beban fincoy, beban md]
            $table->string('total_dana_lpj')->nullable();
            $table->string('status_lpj')->nullable();
            $table->text('dokumentasi_lpj')->nullable(); // foto
            $table->text('problem_identification_lpj')->nullable();
            $table->text('corrective_action_lpj')->nullable();

            //ALTER TABLE `lpjs` ADD `unit_entry_lpj` VARCHAR(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL AFTER `target_downloader_lpj`, ADD `total_unit_entry_lpj` VARCHAR(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL AFTER `unit_entry_lpj`, ADD `revenue_lpj` VARCHAR(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL AFTER `total_unit_entry_lpj`, ADD `total_revenue_lpj` VARCHAR(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL AFTER `revenue_lpj`;
            $table->string('unit_entry_lpj')->nullable();
            $table->string('total_unit_entry_lpj')->nullable();
            $table->string('revenue_lpj')->nullable();
            $table->string('total_revenue_lpj')->nullable();

            //ALTER TABLE `lpjs` ADD `submit_date` DATETIME NULL DEFAULT NULL AFTER `corrective_action_lpj`;
            //UPDATE `lpjs` SET submit_date = updated_at WHERE status_lpj = 2;
            $table->date('submit_date')->nullable();

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
        Schema::dropIfExists('lpjs');
    }
}
