@php
    $sights = $sightList->index();
@endphp

<div class="info-block-body">
@foreach ($sights as $s)
<div class="card-container">
    <div class="card sight-card">
        <img src="data:image/jpeg;base64,{{base64_encode($s->image)}}"/>
        <div class="card-body">
            <div class="row">
                <a href="{{ route('sights.show',$s->id) }}">{{ $s->name }}</a>
            </div>
            <div class="row">
                @if(!empty($s->locality))
                    <span>{{ $s->locality }}</span>
                @endif
                @if(!empty($s->district))
                    <span>{{ $s->district->name }} р-н </span>
                @endif
                @if(!empty($s->area))
                    <span>{{ $s->area->name }} обл. </span>
                @endif
            </div>
            @if($s->count)
            <br/>
            <div class="row">
                @php($getParams = $sightList->filters(['sight'=>$s->id]))
                <a class="link-secondary" href="{{route('activities',$getParams)}}">{{$s->count}} вiдвiдувань</a>
            </div>
            @endif

            @if(!empty($sightList->routeAdd))
                @include('sights.route_add_button',['sight_id'=>$s->id])
            @endif
        </div>
    </div>
</div>
@endforeach
</div>

{{ $sights->links('vendor.pagination.bootstrap-4') }}
