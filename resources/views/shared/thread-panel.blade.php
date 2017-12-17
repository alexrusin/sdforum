<div class="panel panel-default">
    <div class="panel-heading">
    	<div class="level">
    		<span class="flex">
    			<a href="{{route('user-profile', ['user' => $thread->creator->name])}}">{{$thread->creator->name}}</a> posted:
        		{{$thread->title}}
    		</span>
    		<span>{{$thread->created_at->diffForHumans()}}</span>
            @if (Auth::check())
                <form action="{{$thread->path()}}" method="POST">
                    {{csrf_field()}}
                    {{method_field('DELETE')}}
                    <button type="submit" class="btn btn-link">Delete</button>
                </form>
            @endif
    		 
    	</div>
       
    </div>
    <div class="panel-body">
        <div class="body">{{$thread->body}}</div>
    </div>
</div>