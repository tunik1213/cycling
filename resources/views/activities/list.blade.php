@php
    $activities = $actList->index();
@endphp

@extends('layout')
@section('content')

    @if($actList->user)
        <h2>Заїзди @include('user.link',['user'=>$actList->user])</h2>
    @endif

    @if($actList->sight)
        <h4>Локація @include('sights.link',['sight'=>$actList->sight])</h4>
    @endif

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