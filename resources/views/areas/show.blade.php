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

        <div class="col-md-4 col-sm-6 col-xs-12">
            <div>
                <img style="min-height: 100%;" src="{{ route('areas.image',$area->id) }}" alt="Герб {{$area->name}} область"/>
            </div>
            @if(!empty($area->license))
                <div class="license-small">{!! $area->license !!}</div>
            @endif
        </div>

        <div class="col-md-8 col-sm-6 col-xs-12">
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