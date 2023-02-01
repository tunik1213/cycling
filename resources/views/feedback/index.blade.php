@extends('layout')

@section('content')

<h1>Новi вiдгуки</h1>

@foreach($fs as $f)
	<div class="list-group-item list-group-item-action">
		@if(!empty($f->author))
			@include('user.link',['user'=>$f->author])
		@endif
		{{$f->contacts}}
		<p>{{$f->text}}</p>
	</div>
@endforeach
@endsection