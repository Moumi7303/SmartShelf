<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'book_id',
        'reservation_date',
        'expiry_date',
        'queue_position',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'reservation_date' => 'date',
            'expiry_date' => 'date',
            'queue_position' => 'integer',
        ];
    }

    // ─── Relationships ────────────────────────────────────────────

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    // ─── Scopes ───────────────────────────────────────────────────

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['pending', 'approved']);
    }

    // ─── Business Logic ───────────────────────────────────────────

    public function isExpired(): bool
    {
        return $this->expiry_date->isPast() && $this->status !== 'collected';
    }
}
