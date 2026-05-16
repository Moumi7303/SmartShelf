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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->restrictOnDelete();
            $table->foreignId('author_id')->constrained('authors')->restrictOnDelete();
            $table->foreignId('publisher_id')->nullable()->constrained('publishers')->nullOnDelete();
            $table->string('title', 300);
            $table->string('isbn', 20)->unique();
            $table->string('edition', 50)->nullable();
            $table->string('language', 50)->default('English');
            $table->year('publication_year')->nullable();
            $table->text('description')->nullable();
            $table->string('cover_image', 500)->nullable();
            $table->string('barcode', 50)->unique();
            $table->string('shelf_location', 100)->nullable();
            $table->enum('status', ['available', 'unavailable', 'maintenance'])->default('available')->index();
            $table->timestamps();
            $table->softDeletes();

            $table->index('title');
            $table->index(['category_id', 'status']);
            $table->index('author_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
