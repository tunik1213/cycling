<div class="sight-card">
	<a target="_blank" href="{{ route('sights.show',$sight->id) }}">{{ $sight->name }}</a>
	<img class="marker-preview" src="{{route('sights.image',$sight->id)}}">
	@php
		$referer = $_SERVER['HTTP_REFERER'];
		$url_components = parse_url($referer);
		parse_str($url_components['query'], $get_params);
		$routeAdd = $get_params['routeAdd'] ?? null;
		if(empty($routeAdd)) $routeAdd = App\Models\Route::current_editing()->id ?? null;
	@endphp

	@if(!empty($routeAdd))
		@include('sights.route_add_button',['sight_id'=>$sight->id])
		<br/><br/><br/>
	@endif
</div>