@php
    $moderator = Auth::user()->moderator ?? false;
    $lv = App\Models\SightVersion::lastVersion($sight);
    $route = App\Models\Route::current_editing() ?? null;
@endphp


@if (Session::get('success'))
    <div class="alert alert-success">
        <p>{!!Session::get('success')!!}</p>
    </div>
@endif

@if(!$sight->isPublic())
    <div class="alert alert-warning">
        <p>Наразi локація очiкує схвалення модератора</p>
    </div>
@endif

@if(!empty($lv))

    @if($moderator)
        <div class="alert alert-warning">
            <p>Увага! {{$lv->user->link}} {{$lv->user->gender('внiс','внесла')}} змiни до данної локації! Необхiдно перевiрити!</p>
        </div>
    @elseif($lv->user_id == Auth::user()->id)
        <div class="alert alert-warning">
            <p>Спасибi, вашi змiни збережено! Вони вiдобразяться одразу як будуть схваленi модератором</p>
        </div>
    @endif

@endif

<div class="row sight-container">
    <div class="col-lg-4 col-12">
        @if(!empty($sight->image))
            <img class="sight-image" src="{{route('sights.image',$sight->id)}}" alt="Фото {{$sight->name}}">
            @if(!empty($sight->license))
                <div class="lisence-text">{!! $sight->license !!}</div>
            @endif
        @else
            <span>Фото вiдсутнє</span>
        @endif

        <div class="desktop" id="desktop-map"></div>

    </div>

    <div class="col-lg-8 col-xs-12">
        @if($h1 ?? false)
            <h1>{{ $sight->name }}</h1>
        @else
            <div class="sihgt-title">
                <a href="{{ route('sights.show',$sight->id) }}">{{ $sight->name }}</a>
            </div>
        @endif

        {{$sight->categoryLink}}


        <nav aria-label="breadcrumb">
            @if(!empty($sight->area))
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">{{ $sight->area->link }}</li>
                    @if(!empty($sight->district))
                        <li class="breadcrumb-item"><a href="{{ route('districts.show',$sight->district->id) }}">{{ $sight->district->name }} район</a></li>
                    @endif
                    @if(!empty($sight->locality))
                        <li class="breadcrumb-item active" aria-current="page">{{ $sight->locality }}</li>
                    @endif
                </ol>
            @endif
        </nav>

        <div id="sight-author">
            @if($sight->user)
                <strong>Дода{{$sight->user->gender('в','ла')}}: </strong>
                <a href="{{route('userProfile',$sight->user->id)}}">{{ $sight->user->fullname }}</a>
            @else
                <strong>Джерело: </strong>Google
            @endif
        </div>

        <p id="sight-description">
            {!! $sight->description !!}
        </p>

        @if($moderator)
        <div id="sight-radius">
            <strong>Радiус: </strong>{{$sight->radius}}м
        </div>
        
        @endif

        @if($sight->canEdit())
        <div class="row sight-buttons">
            <div class="col">
                <a class="btn btn-primary" href="{{ route('sights.edit',$sight->id) }}"><i class="fas fa-edit"></i> Редагувати</a>
            </div>

            @if($moderator)
            <div class="col">
                <form action="{{ route('sights.destroy',$sight->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt"></i> Видалити</button>
                </form>
            </div>
            @endif

            @if(!empty($route))
                <div class="col add-sight-to-route-button" sight-id="{{$sight->id}}">
                    <a class="btn btn-secondary" href="#"><i class="fas fa-route"></i> В маршрут</a>
                </div>
                
            @endif
        </div>
        @endif

    </div>



    <div class="col-lg-4 col-12 mobile" id="mobile-map">
        {{-- <a href="{{$sight->gm_link()}}" target="_blank" rel="nofollow">
            <img class="sight-image" src="data:image/jpeg;base64,{{base64_encode($sight->map_image)}}" alt="Мапа {{$sight->name}}"> 
        </a> --}}
    </div>

</div>



       