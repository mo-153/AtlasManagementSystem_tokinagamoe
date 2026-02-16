<x-sidebar>
  <!-- スクール予約ページ -->

<div class="vh-100 pt-5" style="background:#ECF1F6;">
  <div class="border w-75 m-auto pt-5 pb-5" style="border-radius:5px; background:#FFF;">
    <div class="w-75 m-auto border" style="border-radius:5px;">

      <p class="text-center">{{ $calendar->getTitle() }}</p>
      <div class="">
        {!! $calendar->render() !!}
      </div>
    </div>
    <div class="text-right w-75 m-auto">
      <input type="submit" class="btn btn-primary" value="予約する" form="reserveParts">
    </div>
  </div>



<!-- ↓予約キャンセルモーダル -->
<div class="modal js-modal">
  <div class="modal__bg js-modal-close"></div>
  <div class="modal__content">
    <form action="{{ route('deleteParts') }}" method="post">
      @csrf
      <div class="w-100">
        <div class="modal-inner-title w-50 m-auto">
          <p>予約日：<span class = "modal-reserve-date"></span>
          </p>
        </div>

        <div class="modal-inner-body w-50 m-auto pt-3 pb-3">
          <p>時間：<span class = "modal-reserve-time"></span></p>
          <p>上記の予約をキャンセルしてもよろしいですか？</p>
        </div>

        <div class="w-50 m-auto cancel-modal-btn d-flex justify-content-between">
          <button class="js-modal-close btn btn-primary d-inline-block" href="">閉じる</button>
          <input type="hidden" class="cancel-modal-hidden" name="reserve_id" value="">
          <input type="submit" class="btn btn-danger d-block" value="キャンセル">
        </div>
        <!-- ↑btn-primary	青色、btn-danger	赤色で色を付けいている -->
      </div>
</form>
</div>
</x-sidebar>
