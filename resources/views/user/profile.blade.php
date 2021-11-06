@extends('layout')
@section('content')

	<div class="row page-title">
		<img class="col-2" src="data:image/jpeg;base64,{{base64_encode($user->avatar)}}" />
		<div class="col-10">
			<h1>{{$user->firstname}} {{$user->lastname}}</h1>
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