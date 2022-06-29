<div class="row">
    @if(empty($userList->route) && empty($userList->sight))
        <a class="link-secondary" href="{{route('sights.list',$getParams)}}">{{$count}} {{$userList->count_link_text()}}</a>
    @else
        <a class="link-secondary" href="{{route('activities',$getParams)}}">{{$count}} вiдвiдувань</a>
    @endif
</div>