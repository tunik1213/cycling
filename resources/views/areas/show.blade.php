@extends('layout')
@section('content')

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>{{ $area->displayName }}</h2>
            </div>
            {{-- <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('areas.index') }}">← Назад</a>
            </div> --}}
        </div>
    </div>

    <div class="row">

        <div class="col-sm-6 col-xs-12">

            <img src="{{ route('areas.image',$area->id) }}" alt="Герб {{$area->name}} область"/>
            
        </div>

        <div class="col-sm-6 col-xs-12">
            <strong>Райони:</strong>
            <div class="list-group">
            @foreach($area->districts as $d)
            <a href="{{ route('districts.show',$d->id) }}" class="list-group-item list-group-item-action">
                {{$d->name}}
            </a>
            @endforeach
            </div>
        </div>

    </div>

    <br />

    <div class="row">
        @include('sights.top',['sightList'=>$topSights])
    </div>

    <div class="row">
        @include('user.top',['userList'=>$topUsers])
    </div>

@endsection