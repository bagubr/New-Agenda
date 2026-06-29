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
        Schema::table('userlogin', function (Blueprint $table) {
            $table->enum('devisi', [
                'Sekretariat',
                'Bidang Perencanaan',
                'Bidang Pengendalian',
                'Bidang Penegakan',
                'Bidang Pelayanan',
                'Bidang Umum'
            ])->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('userlogin', function (Blueprint $table) {
            $table->string('devisi')->nullable()->change();
        });
    }
};
