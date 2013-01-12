<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8" />
<title>ログイン</title>
<script type="text/javascript" src="/html/js/jquery.js"></script>

<script type="text/javascript">
  
  //DOMの準備ができ次第fb-rootにJavaScriptSDKをロードする
  window.onload = function(){
    var element = document.createElement('script');
    element.src = document.location.protocol + '//connect.facebook.net/ja_JP/all.js';
    document.getElementById('fb-root').appendChild(element);
  };
  
  window.fbAsyncInit = function(){
    
    //初期化
    FB.init({
      appId:'312001788889544',
      cookie:true,
      oauth:true,
      status:true,
      xfbml:true
    });
    
    //ログイン状態の確認
    FB.getLoginStatus(function (response){
      if(response.authResponse){
        //ログインしている場合
        top.location.href = "http://synergystudy.me/login/callback";
      }else{
        //未ログインの場合。ログインボタンでログインを促す。
        $("#result").text('Facebookでログインする');
      }      
    });
    
    //実際にログインボタンをクリックした時の処理
    $('#login').click(function(){
      FB.login(function (response){
       if(response.authResponse){
         //ログイン済みの場合の処理
         top.location.href = "http://synergystudy.me/login/callback";
       }
      },{scope:'publish_stream,user_groups,user_education_history'})
    });
    
  };
</script>
</head>
<body>
  <div id="fb-root"></div>
  <p><button id="login">ログイン</button></p>
  <div id="result"></div>
  <!--
  <a href="<?php //echo $dialog_url ?>" >ログイン</a><br/>
  <a href="<?php //echo $hogeurl ?>" >ほげログイン</a><br/>
  <a href="<?php //echo $test_url ?>" >ログイン</a><br/>
  <a href="<?php //echo $test_url2 ?>" >ログイン</a><br/>
  <a href="<?php //echo $unko ?>" >うんこログイン</a><br/>
  -->
</body>
</html>