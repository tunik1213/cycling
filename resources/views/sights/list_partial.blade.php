@php
    $sights = $sightList->index();
@endphp


@foreach ($sights as $s)
<div class="card-container">
    <div class="card sight-card">
        <img src="data:image/jpeg;base64,{{base64_encode($s->image)}}"/>
        <div class="card-body">
            <div class="row">
                <a href="{{ route('sights.show',$s->id) }}">{{ $s->name }}</a>
            </div>
            @if($s->count)
            <div class="row">
                @php($getParams = $sightList->filters(['sight'=>$s->id]))
                <a class="link-secondary" href="{{route('activities',$getParams)}}">{{$s->count}} вiдвiдувань</a>
            </div>
            @endif
        </div>
    </div>
</div>
@endforeach

{{ $sights->links('vendor.pagination.bootstrap-4') }}
