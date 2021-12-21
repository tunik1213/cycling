@php($users = $userList->index())

    @foreach ($users as $u)
    <div class="card-container">
        <div class="card user-card">
            <div class="card-title d-flex justify-content-center">
                <img class="user-avatar" src="data:image/jpeg;base64,{{base64_encode($u->avatar)}}"/>
                {{$u->link}}
            </div>
          
          <div class="card-body">
            {{$u->count_link ?? ''}}
            <div class="row">
                {{$u->stravalink}}
            </div>
          </div>
        </div>
    </div>
@endforeach


{{ $users->links('vendor.pagination.bootstrap-4') }}

