@extends('layout')
@section('content')

	<div class="container page-title">
		<div class="row">
			<div class="col col-3 col-lg-2">
				<img src="data:image/jpeg;base64,{{base64_encode($user->avatar)}}" />
			</div>
			<div class="col col-lg-9">
				<h1>{{$user->fullname}}</h1>
				{{$user->stravaLink}}
			</div>
		</div>
	</div>

	<div class="row">
		@include('sights.top',['topSights'=>$topSightsVisited])
	</div>

	@if($topSightsAuthor->isNotEmpty())
	<div class="row">
		@include('sights.top',['topSights'=>$topSightsAuthor])
	</div>
	@endif

@endsection