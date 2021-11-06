@extends('layout')

@section('head')
	<title>{{env('APP_NAME')}}: {{$category->name}}</title>
@endsection

@section('content')

<h1>Категорія: {{$category->name}}</h1>
<p>{{$category->description}}</p>
  
@include('sights.list_partial')      
        

@endsection