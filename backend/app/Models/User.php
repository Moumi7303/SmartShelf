<?php

namespace App\Models;

use App\Traits\Auditable;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Fillable(['name', 'email', 'phone', 'password', 'role_id', 'branch_id', 'status', 'email_verified_at'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes, HasApiTokens, Auditable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ─── Relationships ────────────────────────────────────────────

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function member(): HasOne
    {
        return $this->hasOne(Member::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    public function loginLogs(): HasMany
    {
        return $this->hasMany(LoginLog::class);
    }

    // ─── Scopes ───────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByRole($query, string $roleName)
    {
        return $query->whereHas('role', fn ($q) => $q->where('name', $roleName));
    }

    public function scopeByBranch($query, int $branchId)
    {
        return $query->where('branch_id', $branchId);
    }

    // ─── Accessors ────────────────────────────────────────────────

    public function getFormattedNameAttribute(): string
    {
        return ucwords(strtolower($this->name));
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'active' => '✅ Active',
            'inactive' => '⏸ Inactive',
            'suspended' => '🚫 Suspended',
            default => ucfirst($this->status),
        };
    }

    public function getInitialsAttribute(): string
    {
        return collect(explode(' ', $this->name))
            ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
            ->take(2)
            ->join('');
    }

    // ─── RBAC Helpers ─────────────────────────────────────────────

    public function hasRole(string $roleName): bool
    {
        return $this->role && $this->role->name === $roleName;
    }

    public function hasAnyRole(array $roleNames): bool
    {
        return $this->role && in_array($this->role->name, $roleNames);
    }

    public function hasPermission(string $permissionName): bool
    {
        if ($this->hasRole('super_admin')) return true;
        if (! $this->role) return false;

        return $this->role->permissions->contains('name', $permissionName);
    }

    public function hasAnyPermission(array $permissions): bool
    {
        if ($this->hasRole('super_admin')) return true;
        if (! $this->role) return false;

        foreach ($permissions as $permission) {
            if ($this->role->permissions->contains('name', $permission)) {
                return true;
            }
        }
        return false;
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super_admin');
    }

    public function isBranchAdmin(): bool
    {
        return $this->hasRole('branch_admin');
    }

    public function isLibrarian(): bool
    {
        return $this->hasRole('librarian');
    }

    public function isStudentMember(): bool
    {
        return $this->hasRole('student_member');
    }

    public function isStaff(): bool
    {
        return $this->hasAnyRole(['super_admin', 'branch_admin', 'librarian']);
    }

    /**
     * Get the dashboard route name based on user role.
     */
    public function getDashboardRoute(): string
    {
        return match ($this->role?->name) {
            'super_admin'    => 'dashboard.super-admin',
            'branch_admin'   => 'dashboard.branch-admin',
            'librarian'      => 'dashboard.librarian',
            'student_member' => 'dashboard.student',
            default          => 'dashboard',
        };
    }
}
