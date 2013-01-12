
function check(){
  if(window.confirm('この内容で記録してよろしいですか？')){
    return true;
  }else{
    return false;
  }
}

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