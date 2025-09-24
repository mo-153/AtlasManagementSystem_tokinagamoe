<x-sidebar>
  <!-- ログインユーザーのスクール予約確認ページ -->
<div class="w-75 m-auto">
  <div class="w-100">
    <p>{{ $calendar->getTitle() }}</p>
    <p>{!! $calendar->render() !!}</p>
  </div>
</div>
</x-sidebar>
