<?php

namespace App\Models;

use Database\Factories\MemberFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Member extends Model
{
    /** @use HasFactory<MemberFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'membership_id',
        'student_id',
        'department',
        'semester',
        'address',
        'membership_status',
        'joined_at',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'joined_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    // ─── Relationships ────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function fines(): HasMany
    {
        return $this->hasMany(Fine::class);
    }

    // ─── Scopes ───────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('membership_status', 'active');
    }

    public function scopeExpired($query)
    {
        return $query->where('membership_status', 'expired');
    }

    public function scopeByDepartment($query, string $department)
    {
        return $query->where('department', $department);
    }

    // ─── Accessors ────────────────────────────────────────────────

    public function getIsActiveAttribute(): bool
    {
        return $this->membership_status === 'active';
    }

    public function getIsExpiredAttribute(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function getTotalUnpaidFinesAttribute(): float
    {
        return (float) $this->fines()->where('status', 'unpaid')->sum('total_amount');
    }
}
