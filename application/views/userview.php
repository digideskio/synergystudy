<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8" />
  <link rel="stylesheet" type="text/css" href="/html/css/bootstrap.css" />
  <link rel="stylesheet" type="text/css" href="/html/css/common.css" />
  <link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/themes/base/jquery.ui.core.css" />
  <link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/themes/base/jquery.ui.theme.css" />	
  <link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/themes/base/jquery.ui.datepicker.css" />
  <link rel="stylesheet" type="text/css" href="/html/css/user.css" />
  <script type="text/javascript" src="/html/js/jquery.js"></script>
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js"></script>
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1/i18n/jquery.ui.datepicker-ja.min.js"></script>
  <script type="text/javascript" src="/html/js/facebook.js"></script>
  <script type="text/javascript" src="/html/js/logout.js"></script>
  <script type="text/javascript" src="/html/js/scrolltop.js"></script>
  <script type="text/javascript" src="/html/js/user.js"></script>
  <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    
  <title>ユーザー専用ページ</title>
</head>
<body>
  <!--Facebook SDK-->
  <div id="fb-root"></div>
  
  <script>
    //何のコードか忘れた
    (function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/ja_JP/all.js#xfbml=1&appId=337123179687256";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
  </script>
  
  <!--=======================ナビゲーションバー=======================-->
　<?php echo $header ?>

  <!--=======================コンテンツ=======================-->
  <div class="container" style="padding:50px;">
   
    <h1><?php echo $fb_name ?>さん専用のページです</h1>
    
    <div id="graph-head" class="clearfix">
      <div class="sub-head-image">
        <img src="/html/imgs/user2.png" height="30px" width="30px"/>
      </div><!-- .sub-head-image -->
      <div class="sub-head-title">
        <h4 class="sub-header">自分の学習記録を振り返る</h4>
      </div><!-- .sub-head-title -->
    </div><!-- #graph-head -->
    
    <div id="graph">
      <div id="graph-content">
        
        <ul class="nav nav-tabs" id="tab-menu">
          <li class="active"><a href="#le-time">学習時間</a></li>
          <li><a href="#self-review">自己評価</a></li>
          <li><a href="#comments">学習内容とコメント</a></li>
          <!--<li><a href="#plan">学習計画</a></li>-->
          <li><a href="#goal">学習目標</a></li>
          <!--<li><a href="#goal-edit">学習目標の設定・編集</a></li>-->
        </ul>
        
        <div class="tab-content">
          
          <div class="tab-pane active clearfix" id="le-time">
            <?php include('user/letime-graph.php') ?>
          </div><!-- #letime -->
          
          <div class="tab-pane clearfix" id="self-review">
            <?php include('user/selfreview-graph.php') ?>
          </div><!-- #self-review -->
                    
          <div class="tab-pane clearfix" id="comments">
            <?php include('user/comment-graph.php') ?>
          </div><!-- #comment -->
          
          <!--
          <div class="tab-pane clearfix" id="plan">
            <?php //include('user/plan-graph.php') ?>
          </div>--><!-- #plan -->
          
          <div class="tab-pane clearfix" id="goal">
            <?php //include('user/goal-graph.php') ?>            
            <?php if($goals):?>
            <h2>学習目標の閲覧・編集をする</h2>         
            
              <table id="ori-goal-table">
                <tbody>
                  <tr>
                    <th></th>
                    <th>学習目標</th>
                    <th>期日</th>
                    <th>期日までの日数</th>
                    <th></th>
                  </tr>
                  <?php $i = 1 ?>
                  <?php foreach ($goals as $data): ?>
                  <tr id="gid<?php echo $data->goal_id ?>">
                    <td class="goal-number"><?php echo $i ?></td>
                    <td class="goal"><?php echo $data->goal ?></td>
                    <td class="goal-period"><?php echo $data->period ?></td>
                    <td class="goal-diff">
                      <?php if($data->diff > 0): ?>
                       あと<?php echo $data->diff ?>日
                       <?php else: ?>
                       終了
                       <?php endif; ?>
                    </td>
                    <td class="edit-mode"><a onclick="edit_goal('<?php echo $data->goal_id ?>')">編集する</a><br /><a onclick="remove_goal('<?php echo $data->goal_id ?>')">削除する</a>
                    </td>
                  </tr>
                    <?php $i++ ?>
                    <?php endforeach; ?>
                </tbody>
              </table>
            <?php endif;?>
              
              <h2>新しい学習目標の設定をする</h2>
              <span class="notes"><i class="icon-exclamation-sign"></i> 学習目標はいくつでも設定することができます。</span>
              <form id="new-goal" action="/user/submit_goal" method="post">
                <p>1. 学習目標を記入して下さい</p>
                <textarea name="goal" maxlength="250" cols="60" rows="4" placeholder="(例) 秋のTOEIC試験で800点以上をとる" required ></textarea>
                <p>2. 期限を設定して下さい</p>
                <input type="text" name="period" id="datepicker2" class="span2" required /><br />
                <input class="btn btn-primary" type="submit" name="submit_goal" value="この内容で登録する" />
              </form>
              
          </div><!-- #goal -->          
          
        </div><!-- #tab-content -->        
      </div><!-- #graph-content -->
      <script>
        $('#tab-menu a').click(function (e) {
          e.preventDefault();
          $(this).tab('show');
        });
      </script>          
    </div><!-- #graph -->  
  
  <!--フッター-->
  <?php echo $footer ?>
  <input id="fb_id" type="hidden" value="<?php echo $fb_id ?>" />
  </div><!--=== .container ===-->
  <script type="text/javascript" src="/html/js/datepicker.js"></script>
  <script type="text/javascript" src="/html/js/bootstrap.min.js"></script>
</body>
</html>

