@extends('layout')

@section('head')
    <title>{{env('APP_NAME')}}: {{$sightList->title(false)}}</title>
@endsection

@section('content')

    <div class="container info-block">
        <h1 class="info-block-header">{!! $sightList->title() !!}</h1>
        
        @include('sights.list_partial') 
        
    </div>

@endsection


@section('javascript')
@endsection