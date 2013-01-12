
//学習目標を編集モードにする
function edit_goal(goal_id){
  var fb_id = $('#fb_id').val();
  var goal_number = $('#gid' + goal_id + ' td.goal-number').html();
  var goal = $('#gid' + goal_id + ' td.goal').html();
  var goal_period = $('#gid' + goal_id + ' td.goal-period').html();
  var goal_diff = $('#gid' + goal_id + ' td.goal-diff').html();
  
  var new_tr = "<tr id='gid" +goal_id+ "'>\
                  <form action='/user/edit_goal/"+fb_id+"' method='post'>\
                    <td class='goal-number'>"+goal_number+"</td>\
                    <td class='goal'><textarea name='goal' required >" + goal + "</textarea></td>\
                    <td class='goal-period'><input type='text' class='span2' name='period' id='datepicker' required value='"+goal_period+"' /></td>\
                    <td class='goal-diff'>"+goal_diff+"</td>\
                    <input type='hidden' value='"+goal_id+"' name='goal_id' />\
                    <td class='edit-mode'><a onclick=\"edit_undo('"+goal_id+"')\">編集終了</a><br /><input class='btn' name='edit_goal' type='submit' value='更新する' /></td>\
                  </form>\
                </tr>";
  
  $('tr#gid' + goal_id).replaceWith(new_tr);
  $.getScript("/html/js/datepicker.js")  
  //ここでdatepicker.jsを読みこまなくてはいけない（非同期で）
}




//学習目標の編集を終了する
function edit_undo(goal_id){
  var goal_number = $('#gid' + goal_id + ' td.goal-number').html();
  var goal = $('#gid' + goal_id + ' td.goal textarea').html();
  var goal_period = $('#gid' + goal_id + ' td.goal-period input').val();
  var goal_diff = $('#gid' + goal_id + ' td.goal-diff').html();
  var old_tr = "<tr id='gid" +goal_id+ "'>\
                  <td class='goal-number'>"+goal_number+"</td>\
                  <td class='goal'>" + goal + "</td>\
                  <td class='goal-period'>"+goal_period+"</td>\
                  <td class='goal-diff'>"+goal_diff+"</td>\
                  <td class='edit-mode'><a onclick=\"edit_goal('"+goal_id+"')\">編集する</a><br /><a href='/user/remove_goal' onclick=\"remove_goal('"+goal_id+"')\">削除する</a></td>\
                </tr>";
  $('tr#gid' + goal_id).replaceWith(old_tr);
  
}


//学習目標の削除をする
function remove_goal(goal_id){
  var fb_id = $('#fb_id').val();
  if(window.confirm('この学習目標を削除してもよろしいですか？')){
    location.href = "/user/remove_goal/"+goal_id+"/"+fb_id ; 
  }
}