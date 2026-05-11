<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudyClubComment extends Model
{
    protected $table = 'studyclub_comments';
    protected $fillable = ['item_id', 'user_id', 'user_name', 'content', 'status'];

    public function item()
    {
        return $this->belongsTo(StudyClubItem::class, 'item_id');
    }
    
    public function user()
    {
        // Se houver uma tabela de usuários central, podemos relacionar aqui.
        // Como o sistema é "aparte", mantemos o user_id como referência.
        return $this->belongsTo(User::class, 'user_id');
    }
}
