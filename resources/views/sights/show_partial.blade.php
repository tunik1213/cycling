@php
    $moderator = Auth::user()->moderator ?? false;
    $lv = App\Models\SightVersion::lastVersion($sight);
    $route = App\Models\Route::current_editing() ?? null;
    $activities_count = 0;
    if(isset($activities) && $activities != null) {
        $activities_count = $activities->count();
    }
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
    <div class="col-lg-4 col-12 sight-image-container">
        @if(!empty($sight->image))
            <img class="sight-image" src="{{route('sights.image',$sight->id)}}" alt="Фото {{$sight->name}}">
            @if(!empty($sight->license))
                <div class="license-text">{!! $sight->license !!}</div>
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

        @if($activities_count > 0)
            @include('activities.count_badge',['activities'=>$activities])
        @endif

        {{$sight->categoryLink}}

        <nav aria-label="breadcrumb">
            @if(!empty($sight->area))
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><i class="fa-solid fa-location-dot link-secondary"></i>{{ $sight->area->link }}</li>
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
            @if($sight->user_id > 0)
                <strong>Дода{{$sight->user->gender('в','ла')}}: </strong>
                <a href="{{route('userProfile',$sight->user->id)}}">{{ $sight->user->fullname }}</a>
            @else
                <strong>Джерело: </strong>{!! App\Models\Sight::$sources[$sight->user_id] !!}
            @endif
        </div>

        <hr />

        <div id="sight-description">
            {!! $sight->description !!}
        </div>

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



    <div class="container">
        <div class="mobile" id="mobile-map"></div>
    </div>
    

</div>



       