@php
	$name = (empty($activity->name)) ? 'Заїзд' : $activity->name;
    $start_date = \Carbon\Carbon::createFromTimeStamp(strtotime($activity->start_date))->locale('uk_UK')->diffForHumans();
@endphp

<div class="d-flex activity-link-container {{$class ?? ''}}">
	<div class="activity-link-user-img">
		<img class="user-avatar" src="data:image/jpeg;base64,{{base64_encode($activity->user->avatar)}}">
	</div>
	<div class="activity-link-text">
		<div class="activity-link-title">
			<a rel="nofollow" target="_blank" href="https://www.strava.com/activities/{{$activity->strava_id}}">
				{{$name}}
			</a>
			@if($activity->count > 0)
				@php($text = (string)$activity->count . ' ' . nouns_declension($activity->count,'пам\'ятка вiдвiдана','пам\'ятки вiдвiданi','пам\'яток вiдвiдано'))
				<span title="{{$text}}" class="badge">{{$activity->count}}</span>
			@endif
		</div>
		<div class="activity-link-body">
			<span class="link-secondary">{{$start_date}}</span>
		</div>



{{-- 		
			<div class="activity-link-count">
				
				<a href="#" class="link-secondary">{{$activity->count}} {{$text}}</a>
			</div>
		@endif --}}
	</div>
</div>