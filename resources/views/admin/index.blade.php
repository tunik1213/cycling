@extends('layout')
@section('content')

	<h1>Сторiнка адмiнiстратора</h1>
	<div>Доступ на цю сторiнку є тiльки у модераторiв</div>
	<h4>База даних</h4>
	<ul>
		<li><a href="{{route('areas.index')}}">Областi</a></li>
		<li><a href="{{route('districts.index')}}">Райони</a></li>
		<li><a href="{{route('sights.index')}}">Локації</a></li>
		<li><a href="{{route('users.index')}}">Люди</a></li>
		<li><a href="{{route('subcategories.index')}}">Пiдкатегорiї</a></li>
	</ul>

@endsection