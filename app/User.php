<?php

namespace App;

use App\Activity;
use App\Reply;
use App\Thread;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'avatar_path', 'confirmed', 'confirmation_token'
    ];

    protected $casts = [
        'confirmed' => 'boolean'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'email'
    ];

    public function getRouteKeyName()
    {
        return 'name';
    }

    public function threads() 
    {
        return $this->hasMany(Thread::class)->latest(); 
    }

    public function activity() 
    {
        return $this->hasMany(Activity::class);
    }

    public function confirm() 
    {
        $this->confirmed = true;
        $this->confirmation_token = null;
        $this->save();
    }

    public function lastReply() 
    {
        return $this->hasOne(Reply::class)->latest();
    }

    public function read($thread) {
        cache()->forever(
                $this->visitedThreadCacheKey($thread), 
                \Carbon\Carbon::now());

    }

    public function visitedThreadCacheKey($thread) 
    {
        return sprintf("users.%s.visits.%s", $this->id, $thread->id);
    }

    public function getAvatarPathAttribute($value) 
    {
        return $value ? Storage::url($value) : Storage::url('avatars/default.jpg');
    }
}
