<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('arsip_surat', function (Blueprint $table) {
            $table->id();
            $table->integer('no_agenda')->nullable();
            $table->string('no_surat')->nullable();
            $table->string('perihal')->nullable();
            $table->string('asal_surat')->nullable();
            $table->date('tgl_surat')->nullable();
            $table->date('tgl_agenda')->nullable();
            $table->string('keterangan')->nullable();
            $table->string('file_size')->nullable();
            $table->string('file_type')->nullable();
            $table->string('original_name')->nullable();
            $table->string('file');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arsip_surat');
    }
};
