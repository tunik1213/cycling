@php
    
@endphp

@extends('layout')
@section('content')

    <div class="row">
        <h2>Зареєстрованi користувачi</h2>
    </div>

    <div class="row">

        @foreach ($users as $u)
        <div class="card" style="width: 18rem;">
            <div class="card-title">
                {{$u->link}}
            </div>
          
          <div class="card-body">
            <div class="row">
                <span>Зареєстрован {{\Carbon\Carbon::createFromTimeStamp(strtotime($u->created_at))->locale('uk_UK')->diffForHumans()}}</span>
            </div>
            <div class="row">
                {{$u->stravalink}}
            </div>
          </div>
        </div>
        @endforeach

    </div>

    {{ $users->links('vendor.pagination.bootstrap-4') }}

@endsection


@section('javascript')
@endsection