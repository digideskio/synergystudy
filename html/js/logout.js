// ログアウト実行
function logout(){
  FB.logout(function(response) {
    top.location.href = "/login";
  });
}
