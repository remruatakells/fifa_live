<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('visitors', function (Blueprint $table): void {
            $table->boolean('is_blocked')->default(false)->after('user_agent');
            $table->timestamp('blocked_at')->nullable()->after('is_blocked');
            $table->string('block_reason')->nullable()->after('blocked_at');
        });
    }

    public function down(): void
    {
        Schema::table('visitors', function (Blueprint $table): void {
            $table->dropColumn(['is_blocked', 'blocked_at', 'block_reason']);
        });
    }
};
