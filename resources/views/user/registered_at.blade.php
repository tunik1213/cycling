<span>Зареєстрован{{ $user->gender('ий','а') }}
	{{\Carbon\Carbon::createFromTimeStamp(strtotime($user->created_at))->locale('uk_UK')->diffForHumans()}}
</span>