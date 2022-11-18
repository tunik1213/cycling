<div id="comments-container-input">
  <label>Залишити коментар:</label>

  <div class="add-comment-form" main>
    <textarea class="form-control @guest restrict @endguest" rows="2"></textarea>
    <button class="post-comment btn btn-primary">Вiдправити</button>
  </div>
</div>

@if($comments->count() > 0)
        
  <br />

  <div class="info-block">

    <div class="info-block-header">
      <h4> Коментарi</h4>
    </div>

    <div id="comments-list" class="info-block-body list-group">
      @foreach ($comments as $comment)
        @include('comments.show',['comment'=>$comment])
      @endforeach
    </div>
  </div>

@endif
