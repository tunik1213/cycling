@php
    $filter_area_id = $sightList->area->id ?? $sightList->district->area->id ?? null;
    $filter_district_id = $sightList->district->id ?? null;
    $filter_category_id = $sightList->category->id ?? $sightList->subcategory->category->id ?? null;
    $filter_subcategory_id = $sightList->subcategory->id ?? null;
@endphp


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

            @if(!empty($sightList->search))
                <a title="Вiдмiнити пошук" href="{{route('sights.list',$sightList->filters([],['search']))}}" class="enabled-filter">
                    Пошук: {{trim($sightList->search)}}
                    &nbsp;
                    <i class="fas fa-times-circle"></i></a>
                </a>
            @endif

        </div>

        

        <div class="row">
            <div class="col-xl-2 col-lg-3 desktop" id="info-block-sidebar">
                <div id="filter-block">
                    <hr/>
                    <p class="filter-block-title">Розташування</p>
                    <ul id="filter-locations" class="folding">
                        @php($getParams = $sightList->filters([],['area','district']))
                        <li><a href="{{route('sights.list',$getParams)}}">Вся Україна</a></li>
                    @foreach($sightList->locations() as $id=>$area)
                        @php($getParams = $sightList->filters(['area'=>$id],['district']))
                        @php($active=($id == $filter_area_id) ? 'active' : '')
                        @php($bold = ($active) ? 'bold' : '')
                        <li class="{{$active}}">
                            <i class="fas fa-caret-down caret"></i>
                            <a class="{{$bold}}" area-id="{{$id}}" href="{{route('sights.list',$getParams)}}">{{$area['name']}}</a>
                            <ul class="nested folding">
                            @foreach($area['districts'] as $d_id=>$d_name)
                                @php($getParams = $sightList->filters(['district'=>$d_id],['area']))
                                @php($bold = ($filter_district_id == $d_id) ? 'bold' : '')
                                <li><a district-id="{{$d_id}}" class="link-secondary {{$bold}}" href="{{route('sights.list',$getParams)}}">{{$d_name}}</a></li>
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
                        @php($getParams = $sightList->filters(['category'=>$id],['subcategory']))
                        @php($active=($id == $filter_category_id) ? 'active' : '')
                        @php($bold = ($active) ? 'bold' : '')
                        <li class="{{$active}}"><i class="fas fa-caret-down caret"></i>
                            <a class="{{$bold}}" category-id="{{$id}}" href="{{route('sights.list',$getParams)}}">{{$cat['name']}}</a>
                            <ul class="nested folding">
                            @foreach($cat['subcats'] as $s_id=>$s_name)
                                @php($getParams = $sightList->filters(['subcategory'=>$s_id],['category']))
                                @php($bold = ($filter_subcategory_id == $s_id) ? 'bold' : '')
                                <li><a subcategory-id="{{$s_id}}" class="link-secondary {{$bold}}" href="{{route('sights.list',$getParams)}}">{{$s_name}}</a></li>
                            @endforeach
                            </ul>
                        </li>
                    @endforeach
                    <br/>
                </div>

            </div>
            
            <div class="col-xl-10 col-lg-9">

                <div id="search-container">
                    <i class="fas fa-search"></i>
                    <input type="text" id="search-input" class="form-control" placeholder="Пошук">
                </div>

                <div class="row mobile">
                    <a id="toggle-mobile-filters" class="link-secondary" href="#">
                        <i class="fas fa-filter"></i> Фільтрувати список
                    </a>
                </div>

                @if($sightList->isNotEmpty())
                    @include('sights.list_partial') 
                @else
                    <br />
                    <p>На жаль, за Вашим запитом нiчого не знайдено :( </p>
                    <p>Спробуйте змiнити параметри пошуку, або, Ви можете <a href="{{route('sights.create')}}"> додати </a> пам'ятку, якої не вистачає</p>
                    <p>← Повернутись <a href="{{route('main')}}">на головну</a></p>
                @endif
            </div>

        </div>
    </div>

@endsection


@section('javascript')
@endsection