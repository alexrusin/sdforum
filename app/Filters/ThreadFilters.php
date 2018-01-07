<?php

namespace App\Filters;

use App\Filters\Filters;
use App\User;


class ThreadFilters extends Filters
{
	protected $filters = ['by', 'popular', 'unanswered'];
	

	protected function by($username) 
	{
		$user = User::where('name', $username)->firstOrFail();
        
        $this->builder->where('user_id', $user->id);
	}

	protected function popular()
	{
		$this->builder->getQuery()->orders = [];
		$this->builder->orderBy('replies_count', 'desc');
	}

	protected function unanswered()
	{
		return $this->builder->where('replies_count', 0);
	}
}