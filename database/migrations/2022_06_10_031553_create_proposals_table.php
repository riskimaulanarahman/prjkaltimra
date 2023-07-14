<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProposalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proposals', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->string('no_proposal')->nullable(); // NOMOR PROPOSAL nomor proposal / tahun / bulan / tanggal / jenis activity / dealer
            $table->integer('user_proposal')->nullable(); // id pembuat
            $table->integer('user_approval')->nullable(); // id jabatan yg approval
            $table->integer('create_proposal')->nullable(); // jenis yg membuat proposal // null - dealer / 1 - Main Dealer
            $table->boolean('inbox_d')->nullable(); // id pembuat
            $table->boolean('inbox_md')->nullable(); // id pembuat
            $table->string('status_proposal')->nullable(); // 1 draft / 2 Waiting Approval / 3 Partial Approval / 4  Final / 5 Revise (kembali approval terakhir)  / 6 Rejected
            $table->string('status_event_proposal')->nullable(); // 1 Proses Pengajuan / 2 Akan Berjalan / 3 Sedang Berjalan / 4 Selesai
            $table->string('dealer_proposal')->nullable(); // id dealer
            $table->string('lokasi_proposal')->nullable(); // id data lokasi
            $table->string('alamat_proposal')->nullable(); // alamat tambahan
            $table->string('display_proposal')->nullable(); // multi data id display 1, 2, 3
            $table->string('finance_proposal')->nullable(); // multi data id display 1, 2, 3
            $table->string('target_database_proposal')->nullable();
            $table->string('target_penjualan_proposal')->nullable();
            $table->string('target_prospectus_proposal')->nullable();
            $table->string('target_downloader_proposal')->nullable();
            $table->string('periode_start_proposal')->nullable(); // tanggal rencana awal
            $table->string('periode_end_proposal')->nullable(); // tanggal rencana akhir
            $table->string('program_proposal')->nullable();
            $table->string('lat_proposal')->nullable();
            $table->string('long_proposal')->nullable();
            $table->text('keterangan_proposal')->nullable(); // tambahan spertinya
            $table->string('foto_lokasi_proposal')->nullable(); // 1 foto lokasi dari galery
            $table->text('dana_proposal')->nullable(); // [item, beban dealer, beban fincoy, beban md]
            $table->string('total_dana_proposal')->nullable();
            $table->string('penanggung_jawab_proposal')->nullable();
            $table->string('sales_people_proposal')->nullable(); // multi data id sales 1, 2, 3
            $table->string('history_penjualan_proposal')->nullable();
            $table->string('tanggal_status_proposal')->nullable();
            $table->string('kategori_proposal')->nullable();
            $table->string('latar_belakang_proposal')->nullable();
            $table->string('latar_kompetitor_proposal')->nullable();
            $table->string('kondisi_penjualan_m_1_proposal')->nullable();
            $table->string('kondisi_penjualan_m1_proposal')->nullable();
            $table->string('tujuan_proposal')->nullable();
            $table->string('tempat_proposal')->nullable();

            //ALTER TABLE `proposals` ADD `unit_entry_proposal` VARCHAR(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL AFTER `tempat_proposal`, ADD `total_unit_entry_proposal` VARCHAR(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL AFTER `unit_entry_proposal`, ADD `revenue_proposal` VARCHAR(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL AFTER `total_unit_entry_proposal`, ADD `total_revenue_proposal` VARCHAR(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL AFTER `revenue_proposal`;
            $table->string('unit_entry_proposal')->nullable();
            $table->string('total_unit_entry_proposal')->nullable();
            $table->string('revenue_proposal')->nullable();
            $table->string('total_revenue_proposal')->nullable();

            //ALTER TABLE `proposals` ADD `submit_date` DATETIME NULL DEFAULT NULL AFTER `total_revenue_proposal`;
            //UPDATE `proposals` SET submit_date = updated_at WHERE status_proposal = 4;
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
        Schema::dropIfExists('proposals');
    }
}
