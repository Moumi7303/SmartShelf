<?php

namespace App\Models;

use Database\Factories\TransactionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Transaction extends Model
{
    /** @use HasFactory<TransactionFactory> */
    use HasFactory;

    protected $fillable = [
        'transaction_code',
        'member_id',
        'book_copy_id',
        'issued_by',
        'returned_to',
        'issue_date',
        'due_date',
        'return_date',
        'renewal_count',
        'status',
        'remarks',
    ];

    protected function casts(): array
    {
        return [
            'issue_date' => 'date',
            'due_date' => 'date',
            'return_date' => 'date',
            'renewal_count' => 'integer',
        ];
    }

    // ─── Relationships ────────────────────────────────────────────

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function bookCopy(): BelongsTo
    {
        return $this->belongsTo(BookCopy::class);
    }

    public function issuedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function returnedToUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'returned_to');
    }

    public function fines(): HasMany
    {
        return $this->hasMany(Fine::class);
    }

    // ─── Scopes ───────────────────────────────────────────────────

    public function scopeIssued($query)
    {
        return $query->where('status', 'issued');
    }

    public function scopeReturned($query)
    {
        return $query->where('status', 'returned');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue')
                     ->orWhere(function ($q) {
                         $q->where('status', 'issued')
                           ->where('due_date', '<', Carbon::today());
                     });
    }

    public function scopeByMember($query, int $memberId)
    {
        return $query->where('member_id', $memberId);
    }

    // ─── Accessors ────────────────────────────────────────────────

    public function getFormattedDueDateAttribute(): string
    {
        return $this->due_date->format('M d, Y');
    }

    public function getFineStatusAttribute(): string
    {
        if ($this->fines->isEmpty()) return 'No fine';
        $unpaid = $this->fines->where('status', 'unpaid')->sum('total_amount');
        return $unpaid > 0 ? "Unpaid: \${$unpaid}" : 'Cleared';
    }

    // ─── Business Logic ───────────────────────────────────────────

    public function isOverdue(): bool
    {
        return $this->status === 'issued' && $this->due_date->isPast();
    }

    public function calculateFine(float $dailyRate = 1.00): float
    {
        if (! $this->isOverdue()) return 0.0;

        $overdueDays = Carbon::today()->diffInDays($this->due_date);
        return round($overdueDays * $dailyRate, 2);
    }

    public static function generateTransactionCode(): string
    {
        do {
            $code = 'TXN-' . strtoupper(date('Ymd')) . '-' . strtoupper(bin2hex(random_bytes(3)));
        } while (static::where('transaction_code', $code)->exists());

        return $code;
    }
}
