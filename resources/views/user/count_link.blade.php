<div class="row">
    @if(empty($userList->route) && empty($userList->sight))
        <a class="link-secondary" href="{{route('sights.list',$getParams)}}">{{shortNumber($count)}} {{$userList->count_link_text()}}</a>
    @else
        <a class="link-secondary" href="{{route('activities',$getParams)}}">{{shortNumber($count)}} вiдвiдувань</a>
    @endif
</div>