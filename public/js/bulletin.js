// 掲示板関連のJSファイル


$(function () {
  $('.main_categories').click(function () {
    var category_id = $(this).attr('category_id');
    $('.category_num' + category_id).slideToggle();
  });

  $(document).on('click', '.like_btn', function (e) {
    e.preventDefault();
    $(this).addClass('un_like_btn');
    $(this).removeClass('like_btn');
    var post_id = $(this).attr('post_id');
    // var count = $('.like_counts' + post_id).text();
    // var countInt = Number(count);

    // ajaxとは、リロードしないでサーバーとデータのやり取りをするもの
    $.ajax({
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      method: "post",
      url: "/like/post/" + post_id,
      data: {
        post_id: $(this).attr('post_id'),
      },

      // (res)とはレスポンス
    }).done(function (res) {
      // 成功したときの処理
      console.log(res);
      $('.like_counts' + post_id).text(res.likesCount);
    })
      // 失敗したときの処理
      .fail(function (res) {
        console.log('fail');
      });
  });

  $(document).on('click', '.un_like_btn', function (e) {
    e.preventDefault();
    $(this).removeClass('un_like_btn');
    $(this).addClass('like_btn');
    var post_id = $(this).attr('post_id');
    // var count = $('.like_counts' + post_id).text();
    // var countInt = Number(count);

    $.ajax({
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      method: "post",
      url: "/unlike/post/" + post_id,
      data: {
        post_id: $(this).attr('post_id'),
      },
    }).done(function (res) {
      $('.like_counts' + post_id).text(res.likesCount);
    }).fail(function () {

    });
  });

  function CommentCounts() {
    $.get("/api/comment-counts", function (data) {
      $("#postComments").text(data.postCounts);
    });
  }


  // 掲示板投稿の編集のモーダル表示
  $('.edit-modal-open').on('click', function () {
    $('.js-modal').fadeIn();
    // ↑モーダルの中身の表示
    var post_title = $(this).attr('post_title');
    var post_body = $(this).attr('post_body');
    var post_id = $(this).attr('post_id');
    $('.modal-inner-title input').val(post_title);
    $('.modal-inner-body textarea').text(post_body);
    $('.edit-modal-hidden').val(post_id);
    return false;
  });

  $('.js-modal-close').on('click', function () {
    $('.js-modal').fadeOut();
    return false;
  });
});

// 投稿削除
$(document).on("click", "posts-destroy", function (e) {
  if (!confirm("削除してよろしいですか？"));
});
