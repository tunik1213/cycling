@extends('layout')
@section('content')

	<h1>{{$user->firstname}} {{$user->lastname}}</h1>

	<div class="row">
		@include('sights.top')
	</div>

	<div class="row">
		@include('sights.top')
	</div>

@endsection