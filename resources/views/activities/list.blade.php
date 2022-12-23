@php
    $activities = $actList->index();
@endphp

@extends('layout')
@section('content')

    @include('activities.list_header')

    <br />

    <div class="list-group">
    @foreach ($activities as $a)

        @include('activities.link',['activity'=>$a,'class'=>'list-group-item list-group-item-action'])

    @endforeach
    </div>

    {{ $activities->links('vendor.pagination.bootstrap-4') }}

@endsection


@section('javascript')
@endsection