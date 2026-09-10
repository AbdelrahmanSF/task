<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->text('address')->nullable()->after('bio');
            $table->string('service_location')->nullable()->after('address');
            $table->boolean('is_active')->default(true)->after('role');
        });

        Schema::table('services', function (Blueprint $table): void {
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table): void {
            $table->dropSoftDeletes();
        });

        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn(['address', 'service_location', 'is_active']);
        });
    }
};
