<a href="{{ route('districts.show',$district->id) }}">
	{{-- <img class="gerb-link" src="data:image/jpeg;base64,{{base64_encode($district->image)}}"/> --}}
	{{ $district->displayName }} 
</a>