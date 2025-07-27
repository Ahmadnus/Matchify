<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
class User extends Authenticatable implements HasMedia
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable,HasApiTokens,InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'gender'
    ];

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
        ];
    }
    public function chatsAsUserOne()
    {
        return $this->hasMany(Chat::class, 'user_one_id');
    }

    public function chatsAsUserTwo()
    {
        return $this->hasMany(Chat::class, 'user_two_id');
    }

    public function allChats()
    {
        return $this->chatsAsUserOne->merge($this->chatsAsUserTwo);
    }

public function fcmTokens()
{
    return $this->hasMany(FcmToken::class);
}
public function blockedUsers()
{
    return $this->hasMany(Block::class, 'blocker_id');
}

public function blockedBy()
{
    return $this->hasMany(Block::class, 'blocked_id');
}

public function hasBlocked(User $user)
{
    return $this->blockedUsers()->where('blocked_id', $user->id)->exists();
}

public function isBlockedBy(User $user)
{
    return $this->blockedBy()->where('blocker_id', $user->id)->exists();
}
}
