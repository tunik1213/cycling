@php
    $sights = $sightList->index();
@endphp

<div class="row">

    @foreach ($sights as $s)
    <div class="card sight-card" style="width: 18rem;">
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
    @endforeach

</div>

{{ $sights->links('vendor.pagination.bootstrap-4') }}
