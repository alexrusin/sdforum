<div class="panel panel-default">
    <div class="panel-heading">
    	<div class="level">
    		<span class="flex">
    			<a href="{{route('user-profile', ['user' => $thread->creator->name])}}">{{$thread->creator->name}}</a> posted:
        		{{$thread->title}}
    		</span>
    		<span>{{$thread->created_at->diffForHumans()}}</span>
    		 
    	</div>
       
    </div>
    <div class="panel-body">
        <div class="body">{{$thread->body}}</div>
    </div>
</div>