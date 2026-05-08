<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

/**
 * Model: StudyClubLocalAdmin
 * Sistema de autenticação LOCAL e INDEPENDENTE para Study Club
 */
class StudyClubLocalAdmin extends Model
{
    use HasFactory;

    protected $table = 'studyclub_local_admins';

    protected $fillable = [
        'username',
        'password',
        'name',
        'email',
        'is_active',
        'last_login_at',
        'login_attempts',
        'locked_until',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_login_at' => 'datetime',
        'locked_until' => 'datetime',
    ];

    /**
     * Verificar senha
     */
    public function checkPassword(string $password): bool
    {
        return Hash::check($password, $this->password);
    }

    /**
     * Verificar se está bloqueado
     */
    public function isLocked(): bool
    {
        if ($this->locked_until && $this->locked_until->isFuture()) {
            return true;
        }
        return false;
    }

    /**
     * Tempo restante de bloqueio em minutos
     */
    public function lockMinutesRemaining(): int
    {
        if (!$this->isLocked()) {
            return 0;
        }
        return now()->diffInMinutes($this->locked_until);
    }

    /**
     * Registrar tentativa falha
     */
    public function recordFailedAttempt(): void
    {
        $this->increment('login_attempts');

        // Bloquear após 5 tentativas por 15 minutos
        if ($this->login_attempts >= 5) {
            $this->update([
                'locked_until' => now()->addMinutes(15),
                'login_attempts' => 0,
            ]);
        }
    }

    /**
     * Registrar login bem-sucedido
     */
    public function recordSuccess(): void
    {
        $this->update([
            'last_login_at' => now(),
            'login_attempts' => 0,
            'locked_until' => null,
        ]);
    }

    /**
     * Buscar por username
     */
    public static function findByUsername(string $username): ?self
    {
        return self::where('username', $username)
            ->where('is_active', true)
            ->first();
    }
}
