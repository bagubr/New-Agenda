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
        Schema::create('notulen_files', function (Blueprint $table) {
            $table->id();
            $table->string('file');
            $table->string('original_name');
            $table->unsignedBigInteger('notulen_masuk_id')->nullable();
            $table->unsignedBigInteger('notulen_keluar_id')->nullable();
            $table->timestamps();
        });

        Schema::create('notulen_masuk', function (Blueprint $table) {
            $table->id();
            $table->integer('noagenda');
            $table->string('periode')->nullable();
            $table->string('filename')->nullable();
            $table->string('original_name')->nullable();
            $table->text('note')->nullable();
            $table->string('user')->nullable();
            $table->timestamp('tgin')->useCurrent();
        });

        Schema::create('notulen_keluar', function (Blueprint $table) {
            $table->id();
            $table->integer('noagenda');
            $table->string('periode')->nullable();
            $table->string('filename')->nullable();
            $table->string('original_name')->nullable();
            $table->text('note')->nullable();
            $table->string('user')->nullable();
            $table->timestamp('tgin')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notulen_keluar');
        Schema::dropIfExists('notulen_masuk');
        Schema::dropIfExists('notulen_files');
    }
};
