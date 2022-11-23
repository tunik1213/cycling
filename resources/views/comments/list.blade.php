<div id="comments-container-input">
  <label>Залишити коментар:</label>

  <div class="add-comment-form" id="add-comment-form-sample">
    <textarea class="form-control @guest restrict @endguest" rows="2"></textarea>
    <button class="post-comment btn btn-primary">Вiдправити</button>
  </div>
</div>

<br />

<div id="comments-list-container" class="info-block @if($comments->count() == 0) invisible @endif">

  <div class="info-block-header">
    <h4> Коментарi</h4>
  </div>

  <div id="comments-list" class="info-block-body">
    @foreach ($comments as $comment)
      @include('comments.show',['comment'=>$comment])
    @endforeach
  </div>
</div>

