<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Domain Model: StudyClubItem
 * Representa um artigo ou conteúdo dentro de uma edição
 */
class StudyClubItem extends Model
{
    use HasFactory;

    protected $table = 'studyclub_items';

    protected $fillable = [
        'edition_id',
        'category',
        'type',
        'type_label',
        'author',
        'title',
        'resumo',
        'achados',
        'implicacoes',
        'image_path',
        'external_url',
        'likes',
        'comments',
        'icon',
    ];

    protected $casts = [
        'likes' => 'integer',
        'comments' => 'integer',
    ];

    /**
     * Tipos válidos de item
     */
    public const TYPE_ARTICLE = 'article';
    public const TYPE_INTERVIEW = 'interview';
    public const TYPE_SPECIAL = 'special';

    public const TYPES = [
        self::TYPE_ARTICLE,
        self::TYPE_INTERVIEW,
        self::TYPE_SPECIAL,
    ];

    /**
     * Relacionamento: Um item pertence a uma edição
     */
    public function edition(): BelongsTo
    {
        return $this->belongsTo(StudyClubEdition::class, 'edition_id');
    }

    /**
     * Scope: Por tipo
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope: Por categoria
     */
    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Acessor: URL completa da imagem
     */
    public function getImageUrlAttribute(): string
    {
        if (empty($this->image_path)) {
            return asset('imagens/fotos_study/default.jpg');
        }

        if (str_starts_with($this->image_path, 'http')) {
            return $this->image_path;
        }

        return asset('storage/' . $this->image_path);
    }

    /**
     * Acessor: Categoria formatada
     */
    public function getFormattedCategoryAttribute(): string
    {
        return strtoupper($this->category);
    }

    /**
     * Business Rule: Verifica se é artigo
     */
    public function isArticle(): bool
    {
        return $this->type === self::TYPE_ARTICLE;
    }

    /**
     * Business Rule: Verifica se é entrevista
     */
    public function isInterview(): bool
    {
        return $this->type === self::TYPE_INTERVIEW;
    }

    /**
     * Business Rule: Incrementa likes
     */
    public function incrementLikes(): void
    {
        $this->increment('likes');
    }

    /**
     * Business Rule: Incrementa comments
     */
    public function incrementComments(): void
    {
        $this->increment('comments');
    }
}
