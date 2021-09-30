@extends('layout')
@section('content')

	<h1>{{$user->firstname}} {{$user->lastname}}</h1>

	@include('sights.top',['user'=>$user])

@endsection