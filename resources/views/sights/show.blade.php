@extends('layout')

@section('head')
    <title>{{env('APP_NAME')}}: {{$sight->name}}</title>
@endsection

@section('content')

@if (Session::get('success'))
    <div class="alert alert-success">
        <p>{{Session::get('success')}}</p>
        <a class="link-secondary" href="{{route('sights.index')}}">← Повернутись до списку</a>
    </div>
@endif

<div class="row">
    <div class="col-lg-4 col-12">
        @if(!empty($sight->image))
            <img class="sight-image" src="data:image/jpeg;base64,{{base64_encode($sight->image)}}" alt="Фото {{$sight->name}}">
        @else
            <span>Фото вiдсутнє</span>
        @endif

        <div class="desktop">
            <a href="{{$sight->gm_link()}}" target="_blank" rel="nofollow">
            <img class="sight-image" src="data:image/jpeg;base64,{{base64_encode($sight->map_image)}}" alt="Мапа {{$sight->name}}"> 
            </a>
        </div>
    </div>

    <div class="col-lg-8 col-xs-12">
        <h2>{{ $sight->name }}</h2>

        {{$sight->categoryLink}}


        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">{{ $sight->area->link }}</li>
                @if(!empty($sight->district))
                    <li class="breadcrumb-item"><a href="{{ route('districts.show',$sight->district->id) }}">{{ $sight->district->name }} район</a></li>
                @endif
                @if(!empty($sight->locality))
                    <li class="breadcrumb-item active" aria-current="page">{{ $sight->locality }}</li>
                @endif
            </ol>
        </nav>

        <div id="sight-author">
            <strong>Дода{{$sight->user->gender('в','ла')}}:</strong>
            @if($sight->user)
                <a href="{{route('userProfile',$sight->user->id)}}">{{ $sight->user->fullname }}</a>
            @else
                Google
            @endif
        </div>

        <p id="sight-description">
            {!! $sight->description !!}
        </p>

        @if(Auth::user()->moderator ?? false)
        <div class="row sight-buttons">
            <div class="col">
                <a class="btn btn-primary" href="{{ route('sights.edit',$sight->id) }}"><i class="fas fa-edit"></i> Редагувати</a>
            </div>
            <div class="col">
                <form action="{{ route('sights.destroy',$sight->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt"></i> Видалити</button>
                </form>
            </div>
        </div>
        @endif
    </div>



    <div class="col-lg-4 col-12 mobile">
        <a href="{{$sight->gm_link()}}" target="_blank" rel="nofollow">
            <img class="sight-image" src="data:image/jpeg;base64,{{base64_encode($sight->map_image)}}" alt="Мапа {{$sight->name}}"> 
        </a>
    </div>



    <div class="container">
        @include('user.top',['userList'=>$topUsers,'list_title'=>'Топ мандрiвникiв'])
    </div>

</div>
        
        

@endsection