<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('visitors', function (Blueprint $table): void {
            $table->string('api_key_hash', 64)->nullable()->after('email');
            $table->string('api_key_suffix', 12)->nullable()->after('api_key_hash');
        });
    }

    public function down(): void
    {
        Schema::table('visitors', function (Blueprint $table): void {
            $table->dropColumn(['api_key_hash', 'api_key_suffix']);
        });
    }
};
