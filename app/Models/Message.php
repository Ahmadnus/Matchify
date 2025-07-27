<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    /** @use HasFactory<\Database\Factories\MessageFactory> */
    use HasFactory;

    protected $fillable = ['message', 'chat_id', 'sender_id','receiver_id'];
    public function chat()
{
    return $this->belongsTo(Chat::class);
}
public function sender()
{
    return $this->belongsTo(User::class, 'sender_id');
}

}
