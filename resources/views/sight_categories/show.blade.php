@extends('layout')
@section('content')

<h1>{{$category->name}}</h1>
<p>{{$category->description}}</p>
  
@include('sights.list_partial',['sights'=>$category->sights()->paginate(24)])      
        

@endsection