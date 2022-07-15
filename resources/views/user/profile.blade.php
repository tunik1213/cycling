@section('head')
	<title>{{env('APP_NAME')}}: {{$user->fullname}}</title>
@endsection


@extends('layout')
@section('content')

	<div class="container page-title">
		<div class="row">
			<div class="col col-3 col-lg-2 col-xl-1">
				<img src="data:image/jpeg;base64,{{base64_encode($user->avatar)}}" />
			</div>
			<div class="col col-lg-9">
				<h1>{{$user->fullname}}</h1>
				{{$user->stravaLink}}
				@php($stats = $user->stats())
			
			</div>
		</div>

		<div class="row user-stats">
			<div class="col-md-3 col-sm-6">
				<a class="user-stat-container link-secondary" href="{{route('activities',['user'=>$user->id])}}">
					<div class="stats-name">Заїзди</div>
					<div class="stats-icon"><i class="fa-solid fa-person-biking"></i></div>
					<div class="stats-value">{{$stats['activities']}}</div>
				</a>
			</div>
			<div class="col-md-3 col-sm-6">
				<a class="user-stat-container link-secondary" href="{{route('sights.list',['user'=>$user->id])}}">
					<div class="stats-name">Локацiї</div>
					<div class="stats-icon"><i class="fa-solid fa-location-dot"></i></div>
					<div class="stats-value">{{$stats['sights']}}</div>
				</a>
			</div>
			<div class="col-md-3 col-sm-6">
				<a class="user-stat-container link-secondary" href="{{route('areas.list',['user'=>$user->id])}}">
					<div class="stats-name">Областi</div>
					<div class="stats-icon"><i class="fa-solid fa-font-awesome"></i></div>
					<div class="stats-value">{{$stats['areas']}}</div>
				</a>
			</div>
			<div class="col-md-3 col-sm-6">
				<a class="user-stat-container link-secondary" href="#">
					<div class="stats-name">Райони</div>
					<div class="stats-icon"><i class="fa-solid fa-font-awesome"></i></div>
					<div class="stats-value">{{$stats['districts']}}</div>
				</a>
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