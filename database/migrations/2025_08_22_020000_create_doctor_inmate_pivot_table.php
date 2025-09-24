<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('doctor_inmate', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('inmate_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['doctor_id','inmate_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctor_inmate');
    }
};
