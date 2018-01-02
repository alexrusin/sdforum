@extends('layouts.app')

@section('content')
<thread-view inline-template :initial-replies-count="{{$thread->replies_count}}">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                @include('shared.thread-panel')
                
                <replies :data="{{$thread->replies}}" @removed="repliesCount--"></replies>

                {{--{{$replies->links()}}--}}

                @if(auth()->check())
                    <form method="POST" action="{{$thread->path() .'/replies'}}" style="margin-bottom: 1.5em;">
                        {{csrf_field()}}
                        <div class="form-group">
                            <textarea name="body" id="body" class="form-control" placeholder="Have something to say?" rows="5"></textarea>
                        </div>
                        <button type="submit" class="btn btn-default">Post</button>
                    </form>
                @else
                    <p class="text-center">Please <a href="{{route('login')}}">sign in</a> to participate</p>
                @endif
            </div>
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <p>
                            This thread was published {{$thread->created_at->diffForHumans()}} by <a href="#">{{$thread->creator->name}}</a>, and currently has <span v-text="repliesCount"></span> {{str_plural('comment', $thread->replies_count)}}
                        </p>
                    </div>
                </div>
            </div>
        </div>    
    </div>
</thread-view>
@endsection