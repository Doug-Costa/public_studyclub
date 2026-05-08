<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model: StudyClubAdmin
 * Controla permissões de administrador do Study Club
 */
class StudyClubAdmin extends Model
{
    use HasFactory;

    protected $table = 'studyclub_admins';

    protected $fillable = [
        'email',
        'name',
        'is_active',
        'role',
        'last_login_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_login_at' => 'datetime',
    ];

    /**
     * Verifica se o usuário é admin
     */
    public static function isAdmin(string $email): bool
    {
        return self::where('email', $email)
            ->where('is_active', true)
            ->exists();
    }

    /**
     * Verifica se o usuário é super admin
     */
    public static function isSuperAdmin(string $email): bool
    {
        return self::where('email', $email)
            ->where('is_active', true)
            ->where('role', 'admin')
            ->exists();
    }

    /**
     * Registrar último login
     */
    public function recordLogin(): void
    {
        $this->update(['last_login_at' => now()]);
    }
}
