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
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->string('model_type')->nullable()->after('action');
            $table->unsignedBigInteger('model_id')->nullable()->after('model_type');
            $table->text('user_agent')->nullable()->after('ip_address');
            
            if (Schema::hasColumn('audit_logs', 'module')) {
                $table->dropColumn('module');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->string('module', 50)->nullable()->after('action');
            $table->dropColumn(['model_type', 'model_id', 'user_agent']);
        });
    }
};
