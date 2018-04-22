<?php

namespace App;

use App\Channel;
use App\Events\ThreadReceivedNewReply;
use App\RecordsVisits;
use App\Reply;
use App\ThreadSubscription;
use App\Traits\RecordsActivity;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use RecordsActivity, RecordsVisits;
    
	protected $guarded = [];

    protected $with = ['creator', 'channel'];

    protected $appends = ['isSubscribedTo'];

    protected $casts = [
        'locked' => 'boolean'
    ];

    protected static function boot()
    {
        parent::boot();

        // static::addGlobalScope('replyCount', function($builder){
        //     $builder->withCount('replies');
        // });

        static::deleting(function($thread){
            $thread->replies->each->delete();
        });

        static::created(function($thread){
            $thread->update(['slug' => $thread->title]);
        });
    }

    public function getIsSubscribedToAttribute()
    {
        return $this->subscriptions()
                ->where('user_id', auth()->id())
                ->exists();
    }
	
    public function path()
    {

        return "/threads/{$this->channel->slug}/{$this->slug}";
    	
    }

    public function replies()
    {
    	return $this->hasMany(Reply::class);
    }


    public function creator()
    {
    	return $this->belongsTo(User::class, 'user_id');
    }

    public function addReply($reply) 
    {
    	$reply = $this->replies()->create($reply);

        event(new ThreadReceivedNewReply($reply, $this));

        return $reply;      
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

    public function subscribe($userId = null) 
    {
        $this->subscriptions()->create([
            'user_id' => $userId ?: auth()->id()
        ]);

        return $this;
    }

    public function unsubscribe($userId = null)
    {
        $this->subscriptions()
            ->where('user_id', $userId ?: auth()->id())
            ->delete();
    }

    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
    }

    public function hasUpdatesFor($user) 
    {

        $key = $user->visitedThreadCacheKey($this);

        return $this->updated_at > cache($key);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function setSlugAttribute($value) 
    {
        $slug = str_slug($value);

        if (static::whereSlug($slug)->exists()){
            $slug = "{$slug}-" . $this->id;
        }

        $this->attributes['slug'] = $slug;
    }

    public function markBestReply(Reply $reply)     
    {
        $this->update(['best_reply_id' => $reply->id]);
    }
}
