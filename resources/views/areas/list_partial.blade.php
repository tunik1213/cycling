@php($areas = $areaList->index())
<div class="info-block-body">
    @foreach ($areas as $area)
    <div class="card-container">
        <div class="card area-card">
            <div class="card-title d-flex justify-content-center">
                {{$area->link}}
            </div>
          
          <div class="card-body">
            <img class="area-image" src="data:image/jpeg;base64,{{base64_encode($area->image)}}"/>
          </div>
          <div>
            @if($area->sight_count)
                <a href="{{route('sights.list',$areaList->filters(['area'=>$area->id]))}}" class="link-secondary">
                    {{$area->sight_count}} локацiй вiдвiдано
                </a>
            @endif
          </div>
        </div>
    </div>
    @endforeach
</div>

{{ $areas->links('vendor.pagination.bootstrap-4') }}

