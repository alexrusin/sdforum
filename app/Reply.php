<?php

namespace App;

use App\Favorite;
use App\Thread;
use App\Traits\RecordsActivity;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
   use Favoritable;
   use RecordsActivity;
   
   protected $guarded = [];

   protected $with = ['owner', 'favorites'];
   
   public function owner()
   {
   		return $this->belongsTo(User::class, 'user_id');
   }

   public function thread()
   {
   		return $this->belongsTo(Thread::class);
   }
}
