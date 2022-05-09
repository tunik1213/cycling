@extends('layout')
@section('content')
	<div class="alert alert-{{$class}}" role="alert">
	  	{!! $text !!}
	</div>
	<a href="/">← На головну</a>
@endsection
