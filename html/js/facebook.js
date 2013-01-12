
window.fbAsyncInit = function(){

  //アプリの初期化
  FB.init({
    appId:'312001788889544',
    status:true,
    cookie:true,
    oauth:true,
    xfbml:true
  });
    
};


//JavaScript SDKの読み込み
$(function(){
  (function(){
    var e = document.createElement('script');
    e.src = document.location.protocol
            + '//connect.facebook.net/ja_JP/all.js';
    e.async = true;
    document.getElementById('fb-root').appendChild(e);
  }());
});

