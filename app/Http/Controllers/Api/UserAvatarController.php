<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserAvatarController extends Controller
{
    public function store() 
    {
   		request()->validate([
   			'avatar' => 'required|image'
   		]);

      Storage::delete(auth()->user()->getOriginal()['avatar_path']);

   		auth()->user()->update([
   			'avatar_path' => request()->file('avatar')->store('avatars')
   		]);

   		return response('Avatar uploaded', 200);
    }
}
