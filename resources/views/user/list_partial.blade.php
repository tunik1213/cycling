@php($users = $userList->index())
<div class="info-block-body">
    @foreach ($users as $u)
    <div class="card-container">
        <div class="card user-card">
            <div class="card-title d-flex justify-content-center">
                <img class="user-avatar" src="data:image/jpeg;base64,{{base64_encode($u->avatar)}}"/>
                {{$u->link}}
            </div>
          
          <div class="card-body">
            {{$u->count_link ?? ''}}
            {{-- <div class="row">
                {{$u->stravalink}}
            </div> --}}
          </div>
        </div>
    </div>
    @endforeach
</div>

{{ $users->links('vendor.pagination.bootstrap-4') }}

<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-9397290056752587"
     crossorigin="anonymous"></script>
<!-- adaptive -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-9397290056752587"
     data-ad-slot="2502468467"
     data-ad-format="auto"
     data-full-width-responsive="true"></ins>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({});
</script>



