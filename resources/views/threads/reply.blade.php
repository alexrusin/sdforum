<div id="reply-{{$reply->id}}" class="panel panel-default">
    <div class="panel-heading">
		<div class="level">
			<h5 class="flex">
				<a href="{{route('user-profile', ['user' => $reply->owner->name])}}">
					{{$reply->owner->name}}
					</a> said {{$reply->created_at->diffForHumans()}}
			</h5>
			<div>
				
				<form method="POST" action="{{route('favorite-reply', ['reply' => $reply->id])}}">
					{{csrf_field()}}
					<button type="submit" class="btn btn-defalut" {{$reply->isFavorited()? 'disabled' : ''}}>
						{{$reply->favorites_count}} {{str_plural('Favorite', $reply->favorites_count)}}
					</button>
				</form>
			</div>
		</div>
       
    </div>
    <div class="panel-body">
        {{$reply->body}}
    </div>
</div>