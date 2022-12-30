@extends('layout')

@php
	$name = (empty($activity->name)) ? 'Заїзд' : $activity->name;
    $start_date = \Carbon\Carbon::createFromTimeStamp(strtotime($activity->start_date))->locale('uk_UK')->diffForHumans();
    $user = $activity->user;
@endphp

@section('head')
	<title>{{env('APP_NAME')}}: Заїзд {{$user->fullname}} {{$name}}</title>
@endsection

@section('content')

<div class="container info-block">
	<div class="page-title">
		<div>
			<img class="user-avatar-large" src="data:image/jpeg;base64,{{base64_encode($user->avatar)}}" />
		</div>
		<div class="container">
			<h1>{{$name}}</h1>
			<p>{{$user->link}}</p>
			<span>Заїзд {{$start_date}}</span><br />
			<a class="font-color-strava" rel="nofollow" target="_blank" href="https://www.strava.com/activities/{{$activity->strava_id}}">
				strava.com/activities/{{$activity->strava_id}}
			</a>
		</div>
	</div>
	

	@if($sights->isNotEmpty())
		<h3>{{$sights->title()}}</h3>

        <div id="map"></div>
        <div id="map-preview"></div>

        <div class="buttons-container">
		@php($editing_route = App\Models\Route::current_editing())
			<a href="{{route('routes.mergeActivity',$activity->id)}}" class="btn btn-secondary">
				@if(empty($editing_route))
		        	Створити маршрут на основi заїзду
		        @else
		        	Додати точки заїзду в маршрут
		        @endif
	        </a>
        </div>
        <br />

		<div class="row">
			@include('sights.list_partial',[
				'sightList'=>$sights
			])
		</div>
	@endif

	@if($activity->user == Auth::user())
	<div class="container"><br />
		<p>Не вистачає відвіданої локації? Будь-ласка, <a href="{{route('sights.create')}}">додай!</a></p>
	</div>
	@endif
</div>


	




@endsection


@section('javascript')
	@include('sights.map_js',['sightList'=>$sights])
@endsection