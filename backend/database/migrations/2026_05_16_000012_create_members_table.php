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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('membership_id', 30)->unique();
            $table->string('student_id', 30)->unique();
            $table->string('department', 100)->nullable()->index();
            $table->string('semester', 20)->nullable();
            $table->text('address')->nullable();
            $table->enum('membership_status', ['active', 'expired', 'suspended', 'pending'])->default('pending')->index();
            $table->timestamp('joined_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
