<div class="row">

    @foreach ($sights as $s)
    <div class="card" style="width: 18rem;">
        <img src="data:image/jpeg;base64,{{base64_encode($s->image)}}"/>
        <div class="card-body">
            <div class="row">
                <a href="{{ route('sights.show',$s->id) }}">{{ $s->name }}</a>
            </div>
            @if($s->count)
            <div class="row">
                <a class="link-secondary" href="{{route('activities',['user'=>$user->id,'sight'=>$s->id])}}">{{$s->count}} вiдвiдувань</a>
            </div>
            @endif
        </div>
    </div>
    @endforeach

</div>

{{ $sights->links('vendor.pagination.bootstrap-4') }}
