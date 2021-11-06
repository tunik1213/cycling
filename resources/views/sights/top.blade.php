@php
	$user = $topSights->user;
	$loading = false; $itsMe = false;
	if(!empty($user)) {
		$itsMe = ($user->id == Auth::user()->id);
		if ($itsMe) $loading = !$user->allSightsVerified();
	}

	$wich = (empty($topSights->author)? 'вiдвiданих' : 'створених') . ' ';
	$title = 'Топ '.$wich.'пам\'яток'	
@endphp

<div id="top-sights-visited">
	<div class="container info-block">
	<h2 class="info-block-header">{{$title}}</h2>
	<div class="info-block-body">
		@include('sights.list_partial',['sightList'=>$topSights])
	</div>
	<div class="info-block-footer">
		@php($show_total_link = true)

 		@if(!empty($user))

			@if($user->activities->count() == 0)
				@php($show_total_link = false)
				<p>Не вдалося iмпортувати данi @if($itsMe) по Вашим заїздам@endif зi Strava</p>
			@else
				@if($loading)
					@php($show_total_link = false)
					<span class="spinner-border spinner-border-sm" role="status"></span>
					&nbsp;&nbsp;&nbsp;&nbsp;
					<span>Наразi проводиться аналiз Ваших заїздiв</span>
					<script>
						setTimeout(function(){
						   window.location.reload(1);
						}, 20000);
					</script>
				@endif
			@endif
		@endif

		@if($show_total_link)
			<a class="link-secondary" href="{{route('sights.list',$topSights->filters())}}">Переглянути всi</a>
		@endif

	</div>
</div>