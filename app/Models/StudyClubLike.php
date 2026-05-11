<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudyClubLike extends Model
{
    protected $table = 'studyclub_likes';
    protected $fillable = ['item_id', 'user_id'];

    public function item()
    {
        return $this->belongsTo(StudyClubItem::class, 'item_id');
    }
}
