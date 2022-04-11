@section('head')
    <title>{{env('APP_NAME')}}: {{$page->title}}</title>
    <meta name="description" content="{{$page->description}}">
@endsection


@extends('layout')

@section('content')

    
    {!! $page->content!!}
    

@endsection

