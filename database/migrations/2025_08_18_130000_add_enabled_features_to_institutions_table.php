<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('institutions', function (Blueprint $table) {
            $table->json('enabled_features')->nullable()->after('email');
        });
    }
    public function down(): void {
        Schema::table('institutions', function (Blueprint $table) {
            $table->dropColumn('enabled_features');
        });
    }
};
