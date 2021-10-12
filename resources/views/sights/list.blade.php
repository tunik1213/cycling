@php
    
@endphp

@extends('layout')
@section('content')

    <div class="row">
        <h2>{{$user->link}}</h2>
    </div>

    <div class="row">

        @foreach ($sights as $s)
        <div class="card" style="width: 18rem;">
          <img src="{{ route('sights.image',$s->id) }}"/>
          <div class="card-body">
            <div class="row">
                <a href="{{ route('sights.show',$s->id) }}">{{ $s->name }}</a>
            </div>
            @if($s->count)
            <div class="row">
                <a class="link-secondary" href="#">{{$s->count}} вiдвiдувань</a>
            </div>
            @endif
          </div>
        </div>
        @endforeach

    </div>

    {{ $sights->links('vendor.pagination.bootstrap-4') }}

@endsection


@section('javascript')
@endsection