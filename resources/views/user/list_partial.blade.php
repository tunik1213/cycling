<div class="row">

        @foreach ($users as $u)
        <div class="card user-card" style="width: 18rem;">
            <div class="card-title d-flex justify-content-center">
                <img class="user-avatar" src="data:image/jpeg;base64,{{base64_encode($u->avatar)}}"/>
                {{$u->link}}
            </div>
          
          <div class="card-body">
            @if(isset($u->dopInformation))
            <div class="row">
                {{$u->dopInformation}}
            </div>
            @endif
            <div class="row">
                {{$u->stravalink}}
            </div>
          </div>
        </div>
        @endforeach

    </div>

@if(gettype($users)!='array')
    {{ $users->links('vendor.pagination.bootstrap-4') }}
@endif