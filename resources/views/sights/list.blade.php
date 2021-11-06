@extends('layout')

@section('head')
    <title>{{env('APP_NAME')}}: {{$sightList->title(false)}}</title>
@endsection

@section('content')

    <h1>{!! $sightList->title() !!}</h1>

    @include('sights.list_partial') 

@endsection


@section('javascript')
@endsection