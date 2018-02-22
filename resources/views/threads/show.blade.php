@extends('layouts.app')

@section('content')
<thread-view inline-template :initial-replies-count="{{$thread->replies_count}}">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                @include('shared.thread-panel')
                
                <replies @added="repliesCount++" @removed="repliesCount--"></replies>
               
            </div>
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <p>
                            This thread was published {{$thread->created_at->diffForHumans()}} by <a href="#">{{$thread->creator->name}}</a>, and currently has <span v-text="repliesCount"></span> {{str_plural('comment', $thread->replies_count)}}
                        </p>
                        <subscribe-button :active="{{json_encode($thread->isSubscribedTo)}}"></subscribe-button>
                    </div>
                </div>
            </div>
        </div>    
    </div>
</thread-view>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{asset('css/vendor/jquery.atwho.css')}}">
@endpush