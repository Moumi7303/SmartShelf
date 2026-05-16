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
        Schema::create('book_copies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained('books')->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained('branches')->restrictOnDelete();
            $table->string('accession_number', 50)->index();
            $table->string('barcode', 50)->unique();
            $table->enum('availability_status', ['available', 'issued', 'reserved', 'lost', 'damaged'])->default('available')->index();
            $table->enum('condition_status', ['good', 'damaged', 'repair_required'])->default('good');
            $table->date('acquired_date')->nullable();
            $table->timestamps();

            $table->index(['book_id', 'branch_id', 'availability_status'], 'copies_book_branch_status_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_copies');
    }
};
