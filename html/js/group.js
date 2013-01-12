
//コメントの投稿
function post_comment(from_id,to_id,date){
  var log_id = to_id + date;
  var group_id = $('#group_id').val();
  var comment = $('#'+log_id+' :text').val();
  if(comment.length){
    $.post(
      "/ajax/post_comment",
      {
       comment:comment,
       from_id:from_id,
       to_id:to_id,
       group_id:group_id,
       log_id:log_id
      },
      function(data){
        $('div#com-list-'+to_id).append(data);
      }
    );
  }
}



//コメントの出力
$(document).ready(function(){
  //$('.recent_log').each(function(){
  $('li.user-data').each(function(){  
    var log_id = $(this).attr('id');
    var to_id = log_id.slice(0,15);
    var fb_id = $('#fb_id').val();
    $.post(
      "/ajax/output_com",
      {log_id:log_id,fb_id:fb_id},
      function(data){
        if(data){
        $('div#com-list-'+to_id).append(data);
        }
      }
    )
  });
});



//コメントの削除
function remove_com(comment_id,to_id,log_id){
  var fb_id = $('#fb_id').val();
  //var to_id = log_id.slice(0,15);
  if(window.confirm('このコメントを削除してもよろしいですか？')){
    $.ajax({
      type: "POST",
      url: "/ajax/remove_com",
      data:{comment_id:comment_id,log_id:log_id,fb_id:fb_id,to_id:to_id},
      success: function(data){
        $('div#com-list-' + to_id).empty();
        $('div#com-list-' + to_id).append(data);
      }
    });    
  }
}


//「いいね」の付与
function post_like(from_id,to_id,date){
  var group_id = $('#group_id').val();
  var log_id = to_id + date;
  var button = "<button class='not_like btn btn-danger btn-mini' onclick=\"remove_like('" + from_id + "','" + to_id + "','" + date + "')\" ></i> いいねを取り消す</button>";

    $.post(
      "/ajax/post_like",
      {
        from_id:from_id,
        to_id:to_id,
        group_id:group_id,
        log_id:log_id
      },
      function(data){
        $('div#like-list-' + to_id + ' div.like-box').replaceWith(data);
        $('li#'+ log_id +' .like').replaceWith(button);
      }
    );
}


//あとはここを修正すればOK！結構めんどくさい疑惑出ています！
//いいねの削除
function remove_like(from_id,to_id,date){
  var group_id = $('#group_id').val();
  var log_id = to_id + date;
  var button = "<button class='like btn btn-primary btn-mini' onclick=\"post_like('" + from_id + "','" + to_id + "','" + date + "')\" ><i class='icon-thumbs-up'></i> いいね！</button>";
    $.post(
      "/ajax/remove_like",
      {
        group_id:group_id,
        log_id:log_id
      },
      function(data){
        //このままだと、出力された全てのいいねが消されてしまうので、要調整
        $('div#like-list-' + to_id + ' div.like-box').replaceWith(data);
        $('li#'+ log_id +' .not_like').replaceWith(button);
      }
    );  
}


//OK
//いいね記録の出力
$(document).ready(function(){
  $('li.user-data').each(function(){  
    var log_id = $(this).attr('id');
    var to_id = log_id.slice(0,15);
    var fb_id = $('#fb_id').val();
    var group_id = $('#group_id').val();
    $.post(
      "/ajax/output_like",
      {log_id:log_id,fb_id:fb_id,group_id:group_id},
      function(data){
        $('div#like-list-'+to_id).append(data);
      }
    );
  });
});


// OK!!　あとはボタンをリンクに変更するとかそのへん
//「いいねボタン」の出力
$(document).ready(function(){
  $('li.user-data').each(function(){  
    var log_id = $(this).attr('id');
    var to_id = log_id.slice(0,15);
    var date = log_id.slice(15);
    var fb_id = $('#fb_id').val();
    var group_id = $('#group_id').val();
    $.post(
      "/ajax/like_btn",
      {
        log_id:log_id,
        fb_id:fb_id,
        to_id:to_id,
        group_id:group_id,
        date:date
      },
      function(data){
        if(data){
        //$('li#'+log_id).append(data);          
        $('li#'+log_id + ' .like-input-area').prepend(data);
        }
      }
    )
  });
});
