@php
    $created_date = \Carbon\Carbon::createFromTimeStamp(strtotime($notification->created_at))->locale('uk_UK')->diffForHumans();
    $unread_class = ($notification->unread()) ? 'highlight' : '';
@endphp

<div class="d-flex activity-link-container list-group-item list-group-item-action {{ $unread_class }}">
	<div class="activity-link-user-img">
		<img class="user-avatar" src="{{ asset('/images/attention.png') }}">
	</div>
	<div class="activity-link-text">
		<div class="activity-link-title">
			{!! $text !!}
		</div>
		<div class="activity-link-body">
			<span class="link-secondary">{{$created_date}}</span>
		</div>
	</div>
</div>