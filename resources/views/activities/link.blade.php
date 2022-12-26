@php
	$name = (empty($activity->name)) ? 'Заїзд' : $activity->name;
    $start_date = \Carbon\Carbon::createFromTimeStamp(strtotime($activity->start_date))->locale('uk_UK')->diffForHumans();
@endphp

<div class="d-flex activity-link-container {{$class ?? ''}}">
	<div class="activity-link-user-img">
		<a href={{route('userProfile',$activity->user->id)}}>
			<img class="user-avatar" src="data:image/jpeg;base64,{{base64_encode($activity->user->avatar)}}">
		</a>
	</div>
	<a class="activity-link-text" href="{{route('activity',$activity->id)}}">
		<div class="activity-link-title">
			<span>{{$name}}</span>
			@if($activity->sight_count > 0)
				@php($text = (string)$activity->sight_count . ' ' . nouns_declension($activity->sight_count,'пам\'ятка вiдвiдана','пам\'ятки вiдвiданi','пам\'яток вiдвiдано'))
				&nbsp;<span title="{{$text}}" class="badge">{{$activity->sight_count}}</span>
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
	</a>
</div>