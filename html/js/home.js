

$(document).ready(function(){
  var query = window.location.search.substring(1);
  if(query == "state=success"){
    showNotification({
            message: "記録が完了しました",
            type: "success",
            autoClose:true,
            duration:5
          });
  }
});

//リロード処理
function rCheck(){
  if (window.name != "2thwindow"){
    location.reload();
    window.name = "2thwindow";
  }
}

//確認ダイアログの表示
function check(){
  if(window.confirm('この内容で記録してよろしいですか？')){
    return true;
  }else{
    return false;
  }
}

$(function($){
  $("body *").filter(function(){
    return this.title && this.title.length>0;
  }).each(function(){
    var self = $(this), title = self.attr("title");
    self.hover(
      function(e){
        self.attr("title","");
        $("body").append("<div id='title-tip'>"+title+"</div>");
        $("#title-tip").css({
          position: "absolute",
          top: e.pageY+(-15),
          left: e.pageX+15
        });
      },

      //mouseout
      function(){
        self.attr("title",title);
        $("#title-tip").hide().remove();
      }
    );

    self.mousemove(function(e){
      $("#title-tip").css({
        top: e.pageY+(-15),
        left: e.pageX+15
      });
    });
  });

});

//リアルタイムの学習をシェアする
/*
$(document).ready(function(){
  $('.minishare').click(function (){
    var groupid = $('#gid').val();
    var btnid = $(this).val();
    var fb_name = $('#fb_name').val();
        
    $.ajax({
      type: "POST",
      url: "/ajax/mini_share",
      data: {
        groupid:groupid,
        btnid:btnid,
        fb_name:fb_name
      },
      success: function(data){
        if(groupid){
          showNotification({
            message: "グループのウォールに投稿しました",
            type: "success",
            autoClose:true,
            duration:3
          });
        }
      },
      error: function (data) {
        if(groupid){          
          showNotification({
            message: "ウォールへの投稿に失敗しました",
            type: "error", 
            autoClose: true,
            duration: 3
          });
        }
      }
    });
  });
});



//画像エフェクト（記録・個人）
$(document).ready(function() {
  $('#cont-list').ImageOverlay({
    overlay_speed: 'fast',
    overlay_speed_out: 'slow',
    overlay_origin: 'top'
  });
});


//画像エフェクト（グループ）
$(document).ready(function() {
 
    //move the image in pixel -15
    var move = 0;
     
    //zoom percentage, 1.2 =120%
    var zoom = 1.2;
 
    //On mouse over those thumbnail
    $('.item').hover(function() {
         
        //Set the width and height according to the zoom percentage
        width = $('.item').width() * zoom;
        height = $('.item').height() * zoom;
         
        //Move and zoom the image
        $(this).find('img').stop(false,true).animate({'width':width, 'height':height, 'top':move, 'left':move}, {duration:200});
         
        //Display the caption
        $(this).find('div.g_caption').stop(false,true).fadeIn(200);
    },
    function() {
        //Reset the image
        $(this).find('img').stop(false,true).animate({'width':$('.item').width(), 'height':$('.item').height(), 'top':'0', 'left':'0'}, {duration:100});    
 
        //Hide the caption
        $(this).find('div.g_caption').stop(false,true).fadeOut(200);
    });
 
});
*/
