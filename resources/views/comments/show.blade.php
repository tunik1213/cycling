<div class="comment" comment-id="{{$comment->id}}" scrollTo="comment{{$comment->id}}">
    <div class="d-flex comment-header">

        <div class="comment-link-user-img">
            <img class="user-avatar" src="data:image/jpeg;base64,{{base64_encode($comment->author->avatar)}}"/>
        </div>
        <div class="comment-link-text">
            <div class="comment-link-title">
                @include('user.link',['user'=>$comment->author])
            </div>
            <div>
                <span class="nowrap">{{\Carbon\Carbon::createFromTimeStamp(strtotime($comment->created_at))->locale('uk_UK')->diffForHumans()}}</span>
            </div>
        </div>
    </div>

    <div class="comment-body">
        <span> {!! html_entity_decode($comment->text) !!}</span>
    </div>

    <a class="comment-link-reply" href="#">Вiдповiсти</a>

    @if($comment->children->count() > 0)
        @foreach ($comment->children as $child)
            @include('comments.show',['comment'=>$child])
        @endforeach
    @endif

</div>
