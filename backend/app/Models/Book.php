<?php

namespace App\Models;

use Database\Factories\BookFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    /** @use HasFactory<BookFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'author_id',
        'publisher_id',
        'title',
        'isbn',
        'edition',
        'language',
        'publication_year',
        'description',
        'cover_image',
        'barcode',
        'shelf_location',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'publication_year' => 'integer',
        ];
    }

    // ─── Relationships ────────────────────────────────────────────

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    public function publisher(): BelongsTo
    {
        return $this->belongsTo(Publisher::class);
    }

    public function copies(): HasMany
    {
        return $this->hasMany(BookCopy::class);
    }

    public function ebooks(): HasMany
    {
        return $this->hasMany(Ebook::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    // ─── Scopes ───────────────────────────────────────────────────

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeSearch($query, string $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('title', 'like', "%{$term}%")
              ->orWhere('isbn', 'like', "%{$term}%")
              ->orWhere('barcode', 'like', "%{$term}%")
              ->orWhereHas('author', fn ($a) => $a->where('name', 'like', "%{$term}%"));
        });
    }

    public function scopeByCategory($query, int $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeByBranch($query, int $branchId)
    {
        return $query->whereHas('copies', fn ($q) => $q->where('branch_id', $branchId));
    }

    // ─── Accessors ────────────────────────────────────────────────

    public function getCoverImageUrlAttribute(): ?string
    {
        return $this->cover_image
            ? asset('storage/' . $this->cover_image)
            : null;
    }

    public function getAvailabilityLabelAttribute(): string
    {
        $available = $this->copies()->where('availability_status', 'available')->count();
        $total = $this->copies()->count();

        if ($total === 0) return 'No copies';
        if ($available === 0) return 'All checked out';
        return "{$available}/{$total} available";
    }

    // ─── Business Logic ───────────────────────────────────────────

    public function checkAvailability(?int $branchId = null): bool
    {
        $query = $this->copies()->where('availability_status', 'available');

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        return $query->exists();
    }

    public static function generateBarcode(): string
    {
        do {
            $barcode = 'SS-' . strtoupper(bin2hex(random_bytes(5)));
        } while (static::where('barcode', $barcode)->exists());

        return $barcode;
    }
}
