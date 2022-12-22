@php
	$count = $activities->count();
@endphp

<div id="activities-count-badge">
	{{-- <i class="fa-solid fa-person-biking"></i> --}}
	<a href="{{$activities->url(['sight'=>$sight->id])}}" class="ac-badge-rectangle">
		{{$count}}
		{{nouns_declension($count,'вiдвiдування','вiдвiдування','вiдвiдувань')}}
		{{-- локацiї --}}
	</a>
	<span class="ac-badge-triangle"></span>
</div>