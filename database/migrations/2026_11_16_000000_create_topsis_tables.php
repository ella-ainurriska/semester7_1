<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
     public function up(): void
{
    // Tambahkan 4 baris ini di baris paling atas method up()
    \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    Schema::dropIfExists('nilais');
    Schema::dropIfExists('kriterias');
    Schema::dropIfExists('kecamatans');
    \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    // 1. Tabel Master Kecamatan
    Schema::create('kecamatans', function (Blueprint $table) {
        $table->id();
        $table->string('nama');
        $table->decimal('latitude', 10, 7);
        $table->decimal('longitude', 10, 7);
        $table->json('geojson')->nullable();
        $table->timestamps();
    });

    // 2. Tabel Master Kriteria Kemiskinan
    Schema::create('kriterias', function (Blueprint $table) {
        $table->id();
        $table->string('kode', 10);
        $table->string('nama');
        $table->float('bobot');
        $table->enum('tipe', ['benefit', 'cost']);
        $table->timestamps();
    });

    // 3. Tabel Nilai Matriks Alternatif x Kriteria
    Schema::create('nilais', function (Blueprint $table) {
        $table->id();
        $table->foreignId('kecamatan_id')->constrained('kecamatans')->cascadeOnDelete();
        $table->foreignId('kriteria_id')->constrained('kriterias')->cascadeOnDelete();
        $table->double('nilai');
        $table->timestamps();
    });
}
    public function down(): void
    {
        Schema::dropIfExists('nilais');
        Schema::dropIfExists('kriterias');
        Schema::dropIfExists('kecamatans');
    }
};