@section('head')
	<title>{{env('APP_NAME')}}: {{$user->fullname}}</title>
@endsection


@extends('layout')
@section('content')

	<div class="container info-block">
		<div class="row">
			<div class="col-12 col-lg-6">
				<div class="page-title">
					<div class="">
						<img class="user-avatar-large" src="data:image/jpeg;base64,{{base64_encode($user->avatar)}}" />
					</div>
					<div class="container">
						<h1>{{$user->fullname}}</h1>
						{{$user->stravaLink}}
						@php($stats = $user->stats())
					</div>
				</div>

				<div class="user-stats">
					<div class="user-stat-container">
						<a class="link-secondary" href="{{route('activities',['user'=>$user->id])}}">
							<div class="stats-name">Заїзди</div>
							<div class="stats-icon"><i class="fa-solid fa-person-biking"></i></div>
							<div class="stats-value">{{$stats['activities']}}</div>
						</a>
					</div>
					<div class="user-stat-container">
						<a class="link-secondary" href="{{route('sights.list',['user'=>$user->id])}}">
							<div class="stats-name">Локацiї</div>
							<div class="stats-icon"><i class="fa-solid fa-location-dot"></i></div>
							<div class="stats-value">{{$stats['sights']}}</div>
						</a>
					</div>
					<div class="user-stat-container">
						<a class="link-secondary" href="{{route('areas.list',['user'=>$user->id])}}">
							<div class="stats-name">Областi</div>
							<div class="stats-icon"><i class="fa-solid fa-font-awesome"></i></div>
							<div class="stats-value">{{$stats['areas']}}</div>
						</a>
					</div>
					<div class="user-stat-container">
						<a class="link-secondary" href="{{route('districts.list',['user'=>$user->id])}}">
							<div class="stats-name">Райони</div>
							<div class="stats-icon"><i class="fa-solid fa-city"></i></div>
							<div class="stats-value">{{$stats['districts']}}</div>
						</a>
					</div>
				</div>
			</div>

			@if($topSightsVisited->isNotEmpty())

				<div class="col-12 col-lg-6">
					<div id="map"></div>
				</div>
				<div id="map-preview" class="container"></div>

			@endif
			
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


@section('javascript')

    @include('sights.map_js',['sightList'=>$topSightsVisited])

@endsection