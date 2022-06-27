@foreach($routes as $route)
	<div class="row">
		
		<div class="col-12 col-sm-4">
	        @if(!empty($route->logo_image))
	            <img class="route-logo-image" src="{{route('routes.image',['id'=>$route->id,'type'=>'logo'])}}" alt="Веломаршрут {{$route->name}}">
	        @endif
	    </div>

	    <div class="col-12 col-sm-8">
	        <a href="{{route('routes.show',$route->id)}}">{{ $route->name }}</a>

	        <div>
	        	<strong>{{$route->areas()}}</strong>
	        </div>

	        <div>
	            <strong>Дистанцiя: </strong>{{$route->distance}}км
	        </div>

	        <div>
	            <strong>Ґрунт/асфальт: </strong>{{$route->grunt_percent}}/{{100-$route->grunt_percent}}
	        </div>
	        
	        <div>
	            @if($route->user)
	                <strong>Дода{{$route->user->gender('в','ла')}}: </strong>
	                <a href="{{route('userProfile',$route->user->id)}}">{{ $route->user->fullname }}</a>
	            @else
	                <strong>Джерело: </strong>
	            @endif
	        </div>

	        @if(!empty($route->license))
	            {!! $route->license !!}
	        @endif

	    </div>


	</div>
@endforeach