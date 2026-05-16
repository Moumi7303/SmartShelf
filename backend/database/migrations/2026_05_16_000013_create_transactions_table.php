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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_code', 30)->unique();
            $table->foreignId('member_id')->constrained('members')->restrictOnDelete();
            $table->foreignId('book_copy_id')->constrained('book_copies')->restrictOnDelete();
            $table->foreignId('issued_by')->constrained('users')->restrictOnDelete();
            $table->foreignId('returned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->date('issue_date');
            $table->date('due_date')->index();
            $table->date('return_date')->nullable();
            $table->unsignedTinyInteger('renewal_count')->default(0);
            $table->enum('status', ['issued', 'returned', 'overdue', 'lost', 'damaged', 'cancelled'])->default('issued')->index();
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->index(['member_id', 'status']);
            $table->index(['book_copy_id', 'status']);
            $table->index(['issue_date', 'due_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
