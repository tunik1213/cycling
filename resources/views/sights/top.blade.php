@php
	$sights = $user->topSightsVisited();
	$loading = false;
	$itsMe = ($user->id == Auth::user()->id);
	if ($itsMe)	$loading = !$user->allSightsVerified();
@endphp

<br />

<div id="top-sights-visited">
	<div class="container info-block">
	<h2 class="info-block-header">Топ вiдвiданих визначних мiсць</h2>
	<div class="info-block-body">
		@include('sights.list_partial',['sights'=>$sights])
	</div>
	<div class="info-block-footer">
		@if($user->activities->count() == 0)
			<p>Не вдалося iмпортувати данi @if($itsMe) по Вашим заїздам@endif зi Strava</p>
		@else
			@if($loading)
				<span class="spinner-border spinner-border-sm" role="status"></span>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<span>Наразi проводиться аналiз Ваших заїздiв</span>
				<script>
					setTimeout(function(){
					   window.location.reload(1);
					}, 20000);
				</script>
			@else
				<a class="link-secondary" href="{{route('userSights',['id'=>$user->id])}}">Переглянути всi</a>
			@endif
		@endif
	</div>
</div>