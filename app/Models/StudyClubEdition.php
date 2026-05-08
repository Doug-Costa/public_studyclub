<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Domain Model: StudyClubEdition
 * Representa uma edição semanal do Study Club
 */
class StudyClubEdition extends Model
{
    use HasFactory;

    protected $table = 'studyclub_editions';

    protected $fillable = [
        'number',
        'title',
        'description',
        'publish_date',
        'status',
    ];

    protected $casts = [
        'publish_date' => 'date',
        'status' => 'boolean',
        'number' => 'integer',
    ];

    /**
     * Relacionamento: Uma edição tem muitos itens
     */
    public function items(): HasMany
    {
        return $this->hasMany(StudyClubItem::class, 'edition_id');
    }

    /**
     * Scope: Edições ativas
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope: Ordenar por número decrescente
     */
    public function scopeLatestEditions($query)
    {
        return $query->orderBy('number', 'desc');
    }

    /**
     * Scope: Publicadas até hoje
     */
    public function scopePublished($query)
    {
        return $query->where('publish_date', '<=', now());
    }

    /**
     * Acessor: Data formatada
     */
    public function getFormattedDateAttribute(): string
    {
        return $this->publish_date?->format('d/m/Y') ?? '';
    }

    /**
     * Business Rule: Verifica se edição está publicada
     */
    public function isPublished(): bool
    {
        return $this->status && $this->publish_date <= now();
    }
}
