<div class="panel panel-default">
    <div class="panel-heading">
    	<div class="level">
    		<span class="flex">
    			<a href="{{route('user-profile', ['user' => $thread->creator->name])}}">{{$thread->creator->name}}</a> posted:
        		<a href="{{$thread->path()}}">{{$thread->title}}</a>
    		</span>
    		<span>{{$thread->created_at->diffForHumans()}}</span>
            @can ('update', $thread)
                <form action="{{$thread->path()}}" method="POST">
                    {{csrf_field()}}
                    {{method_field('DELETE')}}
                    <button type="submit" class="btn btn-link">Delete</button>
                </form>
            @endcan
    		 
    	</div>
       
    </div>
    <div class="panel-body">
        <div class="body">{{$thread->body}}</div>
    </div>
</div>