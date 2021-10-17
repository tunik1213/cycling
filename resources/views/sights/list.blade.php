@php
    
@endphp

@extends('layout')
@section('content')

    @include('sights.list_partial',['sights'=>$sights]) 

@endsection


@section('javascript')
@endsection