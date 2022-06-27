@extends('layout')

@section('content')

<h1>Велосипеднi маршрути</h1>

@include('routes.list_partial',['routes'=>$routes])

@endsection