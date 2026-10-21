<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class AdminInvitation extends Model
{
    protected $fillable = [
        'email',
        'name',
        'role',
        'custom_permissions',
        'token',
        'invited_by',
        'expires_at',
        'accepted_at',
    ];

    protected function casts(): array
    {
        return [
            'custom_permissions' => 'array',
            'expires_at' => 'datetime',
            'accepted_at' => 'datetime',
        ];
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($invitation) {
            if (empty($invitation->token)) {
                $invitation->token = Str::random(48);
            }
            if (empty($invitation->expires_at)) {
                $invitation->expires_at = now()->addDays(7);
            }
        });
    }

    public function getRoleDetailsAttribute(): array
    {
        return User::ROLES[$this->role] ?? [
            'label' => ucfirst($this->role ?? 'Member'),
            'badge' => 'bg-slate-100 text-slate-800 border-slate-200',
            'description' => '',
        ];
    }

    public function inviter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function isAccepted(): bool
    {
        return !is_null($this->accepted_at);
    }

    public function isPending(): bool
    {
        return !$this->isAccepted() && !$this->isExpired();
    }

    public function getInvitationUrl(): string
    {
        return route('admin.invitations.accept', ['token' => $this->token]);
    }
}
