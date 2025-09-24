<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('prefix')->nullable();
            $table->timestamps();
            $table->unique(['institution_id','name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blocks');
    }
};
