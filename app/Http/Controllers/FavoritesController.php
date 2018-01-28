<?php

namespace App\Http\Controllers;

use App\Favorite;
use App\Reply;
use DB;
use Illuminate\Http\Request;

class FavoritesController extends Controller
{

	public function __construct()
	{
		$this->middleware('auth');
	}

    public function store(Reply $reply) 
    {
    	$reply->favorite();

    	if (request()->wantsJson()) {
    		return response(['status' => 'Favorite created']);
    	} 

    	return back();  	
    }

    public function delete(Reply $reply)
    {
    	$reply->unfavorite();

    	if (request()->wantsJson()) {
    		return response(['status' => 'Favorite deleted']);
    	}
        csrf_token();

    	return back();
    }
}
