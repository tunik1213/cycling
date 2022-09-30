@php($districts = $districtList->index())
<div class="info-block-body">
    @foreach ($districts as $district)
    <div class="card-container">
        <div class="card area-card">
            <div class="card-title d-flex justify-content-center">
                {{$district->link}}
            </div>
          
          <div class="card-body">
            <img class="area-image" src="data:image/jpeg;base64,{{base64_encode($district->image)}}"/>
          </div>
          <div>
            @if($district->sight_count)
                <a href="{{route('sights.list',$districtList->filters(['district'=>$district->id]))}}" class="link-secondary">
                    {{$district->sight_count}} локацiй вiдвiдано
                </a>
            @endif
          </div>
        </div>
    </div>
    @endforeach
</div>

{{ $districts->links('vendor.pagination.bootstrap-4') }}

