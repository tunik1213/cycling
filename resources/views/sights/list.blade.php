@extends('layout')

@section('head')
    <title>{{env('APP_NAME')}}: {{$sightList->title(false)}}</title>
@endsection

@section('content')

    <div class="container info-block">
        <h1 class="info-block-header">{!! $sightList->title() !!}</h1>

        <div class="row">
            <div class="col-xl-2 col-lg-3 info-block-sidebar desktop">
                <div class="filter-block">
                    <p class="filter-block-title">Розташування</p>
                    <ul id="filter-locations" class="folding">
                    @foreach($sightList->locations() as $id=>$area)
                        <li><i class="fas fa-chevron-right caret"></i>
                            @php($getParams = $sightList->filters(['area'=>$id],['district']))
                            <a href="{{route('sights.list',$getParams)}}">{{$area['name']}}</a>
                            <ul class="nested folding">
                            @foreach($area['districts'] as $d_id=>$d_name)
                                @php($getParams = $sightList->filters(['district'=>$d_id],['area']))
                                <li><a class="link-secondary" href="{{route('sights.list',$getParams)}}">{{$d_name}}</a></li>
                            @endforeach
                            </ul>
                        </li>
                    @endforeach
                </div>

                <div class="filter-block">
                    <p class="filter-block-title">Категорiя</p>
                </div>
            </div>
            
            <div class="col-xl-10 col-lg-9">
                @include('sights.list_partial') 
            </div>
        </div>
    </div>

@endsection


@section('javascript')
@endsection