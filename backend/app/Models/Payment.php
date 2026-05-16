<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'fine_id',
        'payment_method',
        'amount',
        'paid_at',
        'received_by',
        'transaction_reference',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'paid_at' => 'datetime',
        ];
    }

    // ─── Relationships ────────────────────────────────────────────

    public function fine(): BelongsTo
    {
        return $this->belongsTo(Fine::class);
    }

    public function receivedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    // ─── Scopes ───────────────────────────────────────────────────

    public function scopeByMethod($query, string $method)
    {
        return $query->where('payment_method', $method);
    }
}
