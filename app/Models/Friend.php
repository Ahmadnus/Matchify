<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Friend extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'friend_id',

    ];

    /**
     * المستخدم الذي أرسل أو استقبل طلب الصداقة.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * المستخدم الصديق.
     */
    public function friend()
    {
        return $this->belongsTo(User::class, 'friend_id');
    }
}