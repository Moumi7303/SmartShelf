<?php

namespace App\Models;

use Database\Factories\BookCopyFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BookCopy extends Model
{
    /** @use HasFactory<BookCopyFactory> */
    use HasFactory;

    protected $fillable = [
        'book_id',
        'branch_id',
        'accession_number',
        'barcode',
        'availability_status',
        'condition_status',
        'acquired_date',
    ];

    protected function casts(): array
    {
        return [
            'acquired_date' => 'date',
        ];
    }

    // ─── Relationships ────────────────────────────────────────────

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    // ─── Scopes ───────────────────────────────────────────────────

    public function scopeAvailable($query)
    {
        return $query->where('availability_status', 'available');
    }

    public function scopeByBranch($query, int $branchId)
    {
        return $query->where('branch_id', $branchId);
    }

    public function scopeByCondition($query, string $condition)
    {
        return $query->where('condition_status', $condition);
    }

    // ─── Accessors ────────────────────────────────────────────────

    public function getIsAvailableAttribute(): bool
    {
        return $this->availability_status === 'available';
    }

    public function getStatusLabelAttribute(): string
    {
        return str_replace('_', ' ', ucfirst($this->availability_status));
    }
}
