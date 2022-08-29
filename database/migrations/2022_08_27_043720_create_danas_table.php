<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDanasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('danas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_kode')->constrained('kodes')->onDelete('cascade');
            $table->foreignId('id_sub_kode')->constrained('sub_kodes')->onDelete('cascade');
            $table->foreignId('id_sub_sub_kode')->constrained('sub_sub_kodes')->onDelete('cascade');
            $table->date('tanggal');
            $table->string('keterangan');
            $table->string('transaksi');
            $table->string('nominal');
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
        Schema::dropIfExists('danas');
    }
}
