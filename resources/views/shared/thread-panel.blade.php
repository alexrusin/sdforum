{{-- Editing question --}}

<div class="panel panel-default" v-if="editing" v-cloak>
    <div class="panel-heading">
        <div class="level">
            <input type="text" v-model="thread.title" class="form-control">             
        </div>
       
    </div>
    <div class="panel-body">
        <div class="form-group">
            <wysiwyg v-model="thread.body" :value="thread.body"></wysiwyg>
             <!-- <textarea class="form-control" rows="10" v-model="thread.body"></textarea> -->
        </div>
       
    </div>
    <div class="panel-footer">
        <div class="level">
            <button type="button" class="btn btn-success btn-xs mr-1" @click="update">Update</button>
            <button type="button" class="btn btn-xs mr-1" @click="cancel">Cancel</button>

            @can ('update', $thread)
                <form action="{{$thread->path()}}" method="POST" class="ml-a">
                    {{csrf_field()}}
                    {{method_field('DELETE')}}
                    <button type="submit" class="btn btn-link">Delete Thread</button>
                </form>
            @endcan
        </div> 
    </div>
</div>

{{-- Viewing question --}}
<div class="panel panel-default" v-else>
    <div class="panel-heading">
    	<div class="level">
            <img src="{{$thread->creator->avatar_path}}" width="25" height="25" class="mr-1">
    		<span class="flex">
    			<a href="{{route('user-profile', ['user' => $thread->creator->name])}}">{{$thread->creator->name}}</a> posted:
        		<a href="{{$thread->path()}}" v-text="title"></a>
    		</span>
    		<span>{{$thread->created_at->diffForHumans()}}</span>	 
    	</div>
       
    </div>
    
    <div class="panel-body" v-html="body"></div>

    <div class="panel-footer" v-if="authorize('owns', thread)">
        <button type="button" class="btn btn-primary btn-xs" @click="editing=true">Edit</button>
    </div>
</div>