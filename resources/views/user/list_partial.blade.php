@php($users = $userList->index())

    @foreach ($users as $u)
    <div class="card-container">
        <div class="card user-card">
            <div class="card-title d-flex justify-content-center">
                <img class="user-avatar" src="data:image/jpeg;base64,{{base64_encode($u->avatar)}}"/>
                {{$u->link}}
            </div>
          
          <div class="card-body">
            @if(isset($u->count))
            <div class="row">
                @php($getParams=$userList->filters(['user'=>$u->id]))
                @if(empty($userList->sight))
                    <a class="link-secondary" href="{{route('sights.list',$getParams)}}">{{$u->count}} пам'яток вiдвiдано</a>
                @else
                    <a class="link-secondary" href="{{route('activities',$getParams)}}">{{$u->count}} вiдвiдувань</a>
                @endif
            </div>
            @endif
            <div class="row">
                {{$u->stravalink}}
            </div>
          </div>
        </div>
    </div>
@endforeach


{{ $users->links('vendor.pagination.bootstrap-4') }}

