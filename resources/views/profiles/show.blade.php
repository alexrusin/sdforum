@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="page-header">
				<h1>{{$profileUser->name}}</h1>
				
				@can('update', $profileUser)
					<form method="POST" action="{{route('avatar', $profileUser)}}" enctype="multipart/form-data">
						{{csrf_field()}}
						<input type="file" name="avatar">
						<button type="submit" class="btn btn-primary">Add Avatar</button>

					</form>
				@endcan

				<img src="{{Storage::url($profileUser->avatar())}}" width="50" height="50"> 

			</div>
			@forelse($activities as $date => $record)
				<h3 class="page-header">
					{{$date}}
				</h3>
				@foreach ($record as $activity)
					@if(view()->exists("profiles.activities.{$activity->type}"))
						@include("profiles.activities.{$activity->type}")
					@endif
				@endforeach
			@empty
				<p>There is no activity for this user yet.</p>
			@endforelse

			
		</div>
	</div>
	
</div>	
@endsection