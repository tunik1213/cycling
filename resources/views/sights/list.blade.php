@extends('layout')

@section('head')
    <title>{{env('APP_NAME')}}: {{$sightList->title(false)}}</title>
@endsection

@section('content')

    <div class="container info-block">
        <h1 class="info-block-header">{!! $sightList->title() !!}</h1>

        <div class="" id="enabled-filters">
            {{-- <span>Вiдбiр:</span> --}}
            
            @include('sights.enabled-filter',['filterName'=>'area'])
            @include('sights.enabled-filter',['filterName'=>'district'])
            @include('sights.enabled-filter',['filterName'=>'category'])
            @include('sights.enabled-filter',['filterName'=>'subcategory'])

        </div>

        <div class="row">
            <div class="col-xl-2 col-lg-3 info-block-sidebar desktop">
                <div class="filter-block">
                    <hr/>
                    <p class="filter-block-title">Розташування</p>
                    <ul id="filter-locations" class="folding">
                        @php($getParams = $sightList->filters([],['area','district']))
                        <li><a href="{{route('sights.list',$getParams)}}">Вся Україна</a></li>
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

                    <hr/>
                    <p class="filter-block-title">Категорія</p>
                    <ul id="filter-locations" class="folding">
                        @php($getParams = $sightList->filters([],['category','subcategory']))
                        <li><a href="{{route('sights.list',$getParams)}}">Всі Категорії</a></li>
                    @foreach($sightList->categories() as $id=>$cat)
                        <li><i class="fas fa-chevron-right caret"></i>
                            @php($getParams = $sightList->filters(['category'=>$id],['subcategory']))
                            <a href="{{route('sights.list',$getParams)}}">{{$cat['name']}}</a>
                            <ul class="nested folding">
                            @foreach($cat['subcats'] as $s_id=>$s_name)
                                @php($getParams = $sightList->filters(['subcategory'=>$s_id],['category']))
                                <li><a class="link-secondary" href="{{route('sights.list',$getParams)}}">{{$s_name}}</a></li>
                            @endforeach
                            </ul>
                        </li>
                    @endforeach
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