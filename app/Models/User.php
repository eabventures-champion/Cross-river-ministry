<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'title',
        'bio',
        'avatar',
        'is_admin',
        'role',
        'custom_permissions',
        'last_login_at',
        'is_active',
    ];

    public const ROLES = [
        'super_admin' => [
            'label' => 'Super Administrator',
            'badge' => 'bg-purple-100 text-purple-800 border-purple-200',
            'description' => 'Full unrestricted access to all ministry systems, settings, financial orders, and team administration.',
        ],
        'admin' => [
            'label' => 'Ministry Administrator',
            'badge' => 'bg-brand-100 text-brand-800 border-brand-200',
            'description' => 'Manages coaching, content, store catalog, inquiries, and media. Cannot invite or remove super admins.',
        ],
        'editor' => [
            'label' => 'Content Editor',
            'badge' => 'bg-green-100 text-green-800 border-green-200',
            'description' => 'Manages blog publications, podcasts, video media library, and testimonials.',
        ],
        'coach' => [
            'label' => 'Ministry Coach',
            'badge' => 'bg-amber-100 text-amber-800 border-amber-200',
            'description' => 'Full administrative access across all ministry modules, site content, store catalog, and media (excluding team & permissions management).',
        ],
    ];

    public const PERMISSIONS = [
        'manage_settings' => 'Site & General Settings',
        'manage_team' => 'Invite & Manage Team Members',
        'manage_coaching' => 'Coaching Services & Coaches',
        'manage_consulting' => 'Consulting & Speaking',
        'manage_media' => 'Blog, Podcasts & Video Library',
        'manage_store' => 'Store Products & Orders',
        'manage_inbox' => 'Inquiries & Contact Submissions',
    ];

    public function isAdmin(): bool
    {
        return (bool) $this->is_admin && $this->is_active;
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function getRoleDetailsAttribute(): array
    {
        return self::ROLES[$this->role] ?? [
            'label' => ucfirst($this->role ?? 'Member'),
            'badge' => 'bg-slate-100 text-slate-800 border-slate-200',
            'description' => '',
        ];
    }

    /**
     * Check if user has permission to manage a specific module.
     */
    public function hasPermission(string $permission): bool
    {
        if (! $this->isAdmin()) {
            return false;
        }

        if ($this->isSuperAdmin()) {
            return true;
        }

        // Custom individual override
        if (is_array($this->custom_permissions) && ! empty($this->custom_permissions)) {
            return (bool) ($this->custom_permissions[$permission] ?? false);
        }

        // Default role mapping
        return match ($this->role) {
            'admin', 'coach' => in_array($permission, [
                'manage_settings',
                'manage_coaching',
                'manage_consulting',
                'manage_media',
                'manage_store',
                'manage_inbox',
            ]),
            'editor' => in_array($permission, [
                'manage_media',
            ]),
            default => false,
        };
    }

    public function avatarUrl(): ?string
    {
        return Media::resolveImageUrl($this->avatar);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

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
            'is_admin' => 'boolean',
            'is_active' => 'boolean',
            'custom_permissions' => 'array',
            'last_login_at' => 'datetime',
        ];
    }
}
