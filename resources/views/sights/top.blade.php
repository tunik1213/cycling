@php
	$sights = $user->topSightsVisited();
	$loading = !$user->allSightsVerified();
@endphp

<br />

<div id="top-sights-visited">
	<div class="container info-block">
	<h2 class="info-block-header">Топ вiдвiданих визначних мiсць</h2>
	<div class="info-block-body">
	@foreach($sights as $s)
		<div class="card" style="width: 18rem;">
		  <img class="card-img-top" src="{{route('sights.image',$s->id)}}">
		  <div class="card-body">
		    <h5 class="card-title"><a href="{{route('sights.show',$s->id)}}">{{$s->name}}</a></h5>
		    <div class="card-text">
		    	<a class="link-secondary" href="{{\App\Models\Activity::link($user->id,$s->id)}}">{{$s->count}} вiдвiдувань</a>
		    </div>
		  </div>
		</div>
	@endforeach
	</div>
	<div class="info-block-footer">
		@if($loading)
			<span class="spinner-border spinner-border-sm" role="status"></span>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<span>Наразi проводиться аналiз Ваших заїздiв</span>
			<script>
				setTimeout(function(){
				   window.location.reload(1);
				}, 5000);
			</script>
		@else
			<a class="link-secondary" href="{{route('userSights',['id'=>$user->id])}}">Переглянути всi</a>
		@endif
	</div>
</div>