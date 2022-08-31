<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubSubKodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_sub_kodes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_sub_kode')->constrained('sub_kodes')->onDelete('cascade');
            $table->string('no_sub_sub_kode')->unique();
            $table->string('nama_sub_sub_kode')->unique();
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
        Schema::dropIfExists('sub_sub_kodes');
    }
}
