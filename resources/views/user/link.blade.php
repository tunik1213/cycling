<a href={{route('userProfile',$user->id)}}>
	<img class="user-avatar" src="data:image/jpeg;base64,{{base64_encode($user->avatar)}}"/>
	{{$user->fullName}}
</a>