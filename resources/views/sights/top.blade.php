@php
	$sights = $user->topSightsVisited();
@endphp

<br />

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
	<a class="link-secondary" href="{{route('userSights',['id'=>$user->id])}}">Переглянути всi</a>
</div>
