@php
    
@endphp

@extends('layout')
@section('content')

    @if($user)
        <h2>Заїзди @include('user.link',['user'=>$user])</h2>
    @endif

    @if($sight)
        <h4>Пам'ятка @include('sights.link',['sight'=>$sight])</h4>
    @endif

    <br />

    <div class="list-group">
    @foreach ($activities as $a)
    <div class="row">

        @include('activities.link',['activity'=>$a,'class'=>'list-group-item list-group-item-action'])

    </div>
    @endforeach
    </div>

    {{ $activities->links('vendor.pagination.bootstrap-4') }}

@endsection


@section('javascript')
@endsection