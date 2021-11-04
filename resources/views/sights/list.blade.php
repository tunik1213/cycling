@php

    $title=$sightList->title();
    $h1 = $title;
    if(!empty($sightList->user)){
        $user = $sightList->user;
        $add = ', якi вiдвiда'.$user->gender('в','ла') . ' ';
        $title .= $add . $user->fullname;
        $h1 .= $add . $user->link;
    }

@endphp

@extends('layout')

@section('head')
    <title>{{env('APP_NAME')}}: {{$title}}</title>
@endsection

@section('content')

    <h1>{!! $h1 !!}</h1>

    @include('sights.list_partial',['sightList'=>$sightList]) 

@endsection


@section('javascript')
@endsection