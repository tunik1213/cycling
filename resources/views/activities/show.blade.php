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
		<div class="row">
			<h1>{{$name}}</h1>
		</div>
		<div class="row">
			<div class="col col-3 col-lg-2 col-xl-1">
				<img src="data:image/jpeg;base64,{{base64_encode($user->avatar)}}" />
			</div>
			<div class="col col-lg-9">
				<p>{{$user->link}}</p>
				<span>Заїзд {{$start_date}}</span><br />
				<a class="font-color-strava" rel="nofollow" target="_blank" href="https://www.strava.com/activities/{{$activity->strava_id}}">
					strava.com/activities/{{$activity->strava_id}}
				</a>
			</div>
			<div class="row">

			</div>
		</div>
	</div>
	<h3>{{$sights->title()}}</h3>
	<div class="row">
		@include('sights.list_partial',[
			'sightList'=>$sights
		])
	</div>
	@if($activity->user == Auth::user())
	<div class="container"><br />
		<p>Не вистачає потрiбної локації? Будь-ласка, <a href="{{route('sights.create')}}">додай!</a></p>
	</div>
	@endif
</div>


	




@endsection