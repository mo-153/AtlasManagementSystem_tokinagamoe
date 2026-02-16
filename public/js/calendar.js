$(function () {
  // 予約キャンセルの時のモーダル表示
  $('.cancel-modal-open').on('click', function () {
    $('.js-modal').fadeIn();
    // ↑モーダルの中身の表示
    var reserve_date = $(this).attr('reserve_date');
    var reserve_time = $(this).attr('reserve_time');
    var reserve_id = $(this).attr('reserve_id');
    $('.modal-reserve-date').text(reserve_date);
    $('.modal-reserve-time').text(reserve_time);
    $('.cancel-modal-hidden').val(reserve_id);
    // ↑.textがテキストとして表示させる、.valが値(value)という意味で表示されず値を送る

    return false;
  });

  $('.js-modal-close').on('click', function () {
    $('.js-modal').fadeOut();
    return false;
  });

});
