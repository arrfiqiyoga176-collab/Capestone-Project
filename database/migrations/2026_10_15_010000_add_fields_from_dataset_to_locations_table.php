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
        Schema::table('locations', function (Blueprint $table) {
            $table->string('id_tempat', 20)->nullable()->after('id');
            $table->string('biaya', 150)->default('Gratis')->after('longitude');
            $table->string('foto')->nullable()->after('jam_operasional');
            $table->string('status', 30)->default('Buka')->after('foto');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->dropColumn(['id_tempat', 'biaya', 'foto', 'status']);
        });
    }
};
