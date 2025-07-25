<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAnswer extends Model
{


    protected $fillable = [
        'user_id',
        'question_id',
        'answer',
    ];

    // العلاقة مع المستخدم
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

}