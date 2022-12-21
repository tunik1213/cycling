@extends('layout')

@section('head')
	<title>{{env('APP_NAME')}}: Мої сповіщення</title>
@endsection

@section('content')

	<h1>Мої сповіщення</h1>
	
	<div class="list=group notifications">
    @foreach($notifications as $n)
    	
    	{{ $n->type::render($n) }}
    	
    @endforeach
	</div>


    @php
    	$notifications->markAsRead();
    @endphp

@endsection


@section('javascript')
<script type="text/javascript">
	$('.notifications .unread').removeClass('unread', {duration:10000})
</script>
@endsection
