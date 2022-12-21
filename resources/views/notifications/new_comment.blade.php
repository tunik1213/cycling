@php
    $created_date = \Carbon\Carbon::createFromTimeStamp(strtotime($notification->created_at))->locale('uk_UK')->diffForHumans();
    $unread_class = ($notification->unread()) ? 'highlight' : '';
@endphp

<div class="d-flex activity-link-container list-group-item list-group-item-action {{ $unread_class }}">
	<div class="activity-link-user-img">
		<a href={{route('userProfile',$author->id)}}>
			<img class="user-avatar" src="data:image/jpeg;base64,{{base64_encode($author->avatar)}}">
		</a>
	</div>
	<div class="activity-link-text">
		<div class="activity-link-title">
			<a href="{{ $comment->url }}">
				<b>{{ $author->fullName }}</b>
				@if(empty($parent_comment))
					{{ $author->gender('прокоментував','прокоментувала') }} локацію <b>{{ $commented_object->name }}</b>
				@else
					відповів на твій коментар
				@endif
			</a>
		</div>
		<div class="activity-link-body">
			<span class="link-secondary">{{$created_date}}</span>
		</div>
	</div>
</div>