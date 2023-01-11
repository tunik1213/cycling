@php
	$itsMe = false;
	$user = $topSights->user;
	if(!empty($user)) {
		$itsMe = ($user->id == (Auth::user()->id ?? null));
	}
	$inProgress = ($itsMe && $topSights->isEmpty() && empty($user->visits_verified_at));

	$wich = (empty($topSights->author)? 'вiдвiданих' : 'створених') . ' ';
	$title = 'Топ '.$wich.'пам\'яток'	
@endphp

<div class="top-sights">
	<div class="container info-block">
		<h2 class="info-block-header">{{$title}}</h2>

		@include('sights.list_partial',['sightList'=>$topSights])

		<div class="info-block-footer">
			@php($show_total_link = true)

	 		@if(!empty($user))

				@if(($user->activities->count() == 0) && ($user->created_at->timestamp < strtotime('-1 hour') ))
					@php($show_total_link = false)
					<p>Не вдалося iмпортувати данi @if($itsMe) по твоїм заїздам@endif зi Strava</p>
				@else

					@if($inProgress)

						@php($show_total_link = false)
						<div id="visits-progress-container">
							<span class="spinner-border spinner-border-sm" role="status"></span>
							<span>Наразi проводиться аналiз твоїх заїздiв</span>
						</div>
						<div class="text-secondary">
							Ця операція працює у фоні, скоро вона завершиться і тобі прийде сповіщення про результат. 
							Зазвичай це займає до кількох хвилин, в залежності від того, як багато в тебе заїздів у Strava
						</div>
						<script id="checkCompleted"></script>

					@endif

				@endif

			@endif

			@if($show_total_link)
				<a class="link-secondary" href="{{route('sights.list',$topSights->filters())}}">Переглянути всi</a>
			@endif
		</div>
	</div>
</div>


