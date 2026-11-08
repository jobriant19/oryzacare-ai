<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->string('visitor_key', 36);       // UUID acak dari cookie, bukan data pribadi
            $table->date('visit_date');              // tanggal (WIB) kunjungan
            $table->dateTime('last_seen_at')->index();
            $table->unique(['visitor_key', 'visit_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
