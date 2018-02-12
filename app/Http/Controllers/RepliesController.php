<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Thread;
use Illuminate\Http\Request;

class RepliesController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth', ['except' => 'index']);
	}

    public function index($channelId, Thread $thread)    
    {
        return $thread->replies()->paginate(20);
    }
	
    public function store($channelId, Thread $thread)
    {

        try {

            $this->validate(request(), [
                'body' => 'required|spamfree'
            ]);

          

            $reply = $thread->addReply([
                'body' => request('body'),
                'user_id' => auth()->id()

            ]);

        } catch (\Exception $e) {
            return response(
                'Sorry, your reply could not be saved at this time', 
                422
            );
        }
        
                
        return $reply->load('owner');
       
    }

    public function update(Reply $reply) 
    {
        $this->authorize('update', $reply);

        $this->validate(request(), [
            'body' => 'required|spamfree'
        ]);

        try {

            $reply->update(request(['body']));
        } catch (\Exception $e) {
            return response(
                'Sorry, your reply could not be updated at this time', 
                422
            );
        }

      
    }

    public function destroy(Reply $reply) 
    {
        $this->authorize('update', $reply);

        $reply->delete();

        if(request()->wantsJson()) {
            return response(['status' => 'Deleted']);
        }

        return back();
    }


}