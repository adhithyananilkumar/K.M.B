<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('inmates', function (Blueprint $table) {
            $table->foreignId('guardian_id')->nullable()->after('institution_id')->constrained('guardians')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('inmates', function (Blueprint $table) {
            $table->dropConstrainedForeignId('guardian_id');
        });
    }
};
