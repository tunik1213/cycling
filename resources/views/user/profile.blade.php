@extends('layout')
@section('content')

	<h1>{{$user->firstname}} {{$user->lastname}}</h1>

	<div class="row">
		@include('sights.top',['topSights'=>$topSightsVisited])
	</div>

	@if($topSightsAuthor->isNotEmpty())
	<div class="row">
		@include('sights.top',['topSights'=>$topSightsAuthor])
	</div>
	@endif

@endsection