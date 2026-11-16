<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kecamatans', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->timestamps();
        });

        Schema::create('kriterias', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->decimal('bobot', 8, 4);
            // benefit = makin besar nilainya, makin miskin (prioritas naik)
            // cost    = makin besar nilainya, makin sejahtera (prioritas turun)
            $table->enum('tipe', ['benefit', 'cost']);
            $table->timestamps();
        });

        Schema::create('nilais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kecamatan_id')->constrained()->cascadeOnDelete();
            $table->foreignId('kriteria_id')->constrained()->cascadeOnDelete();
            $table->decimal('nilai', 14, 4);
            $table->unique(['kecamatan_id', 'kriteria_id']);
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
