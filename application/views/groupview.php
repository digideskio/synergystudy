<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8" />
  <title>グループページ</title>
  <link rel="stylesheet" type="text/css" href="/html/css/bootstrap.css" />
  <link rel="stylesheet" type="text/css" href="/html/css/jquery.neosmart.fb.wall.css" />
  <link rel="stylesheet" type="text/css" href="/html/css/common.css" />
  <link rel="stylesheet" type="text/css" href="/html/css/group.css" />
  
  <script type="text/javascript" src="/html/js/jquery.js"></script>
  <script type="text/javascript" src="/html/js/facebook.js"></script>
  <script type="text/javascript" src="/html/js/logout.js"></script>
  <script type="text/javascript" src="/html/js/scrolltop.js"></script>
  <script type="text/javascript" src="/html/js/jquery.neosmart.fb.wall.js"></script>
  <script type="text/javascript" src="/html/js/group.js"></script>
  <script type="text/javascript" src="https://www.google.com/jsapi"></script>
  <script type="text/javascript">   
    $(function () {
      $('#live-wall').fbWall({
        id:'<?php echo $group_id ?>',
        accessToken:'<?php echo $access_token ?>',
        max:10,
        showComments:	 true,
        useAvatarAlternative:	false,
        avatarAlternative:	 '/html/imgs/avatar-alternative.jpg', 
        avatarExternal:	 '/html/imgs/avatar-external.jpg' 
      });
    });
  </script>
</head>
<body>
  <!--Facebook SDK-->
  <div id="fb-root"></div>
  
  <script>
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
    
    <h1>『<?php echo $groupinfo['name'] ?>』のページ</h1>
    
    <div id="graph-head" class="clearfix">
      <div class="sub-head-image">
        <img src="/html/imgs/bar.png" height="30px" width="30px"/>
      </div><!-- .sub-head-image -->
      <div class="sub-head-title">
        <h4 class="sub-header">グループ内の学習記録をグラフィカルに見る</h4>
      </div><!-- .sub-head-title -->
    </div><!-- #graph-head -->
    
    <div id="graph">
      <div id="graph-content">
        <ul class="nav nav-tabs" id="tab-menu">
          <li class="active"><a href="#sum">総合学習時間</a></li>
          <li><a href="#avg">平均学習時間</a></li>
          <li><a href="#self-review">自己評価</a></li>
          <!--<li><a href="#plan">学習予定</a></li>-->
          <li><a href="#goals">学習目標</a></li>
          <!--<li><a href="#group-log">グループ全体</a></li>-->
          <li><a href="#member">メンバー情報</a></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active clearfix" id="sum">
            <?php include('group/ch_sumgrplt.php') ?>
          </div><!-- #sum -->
          
          <div class="tab-pane clearfix" id="avg">
            <?php include('group/ch_avggrplt.php') ?>            
          </div><!-- #avg -->
          
          <div class="tab-pane clearfix" id="self-review">
            <?php include('group/ch_grpsr.php') ?>
          </div><!-- #self-review -->
          
          <!--
          <div class="tab-pane clearfix" id="plan">
            <?php //include('group/gr_nextplan.php') ?>
          </div>--><!-- #plan -->
          
          <div class="tab-pane clearfix" id="goals">
            <?php include('group/gr_goal.php') ?>
          </div><!-- #goals -->
          
          <!--
          <div class="tab-pane clearfix" id="group-log">
            <?php //include('group/sum_grouplt_chart.php') ?>
          </div>
          -->
          <!-- #group-log -->
            
          <div class="tab-pane" id="member" style="padding-left:10px;">
            <ul class="unstyled">
            <?php foreach ($activemember as $member) : ?>
            <li class="user_box clearfix">
              <div class="user_photo">
                <img title="<?php echo $member->fb_name ?>" src="http://graph.facebook.com/<?php echo $member->fb_id ?>/picture/" width="60px" height="60px"/>
              </div><!-- .user_photo -->
              <div class="user_info">
                <div class="user_name">
                  <strong>名前</strong>　<?php echo $member->fb_name ?>
                </div><!-- .user_name -->
                <div class="started-at">
                  <strong>アプリ利用開始日</strong>　<?php echo arrange_date($member->started_at) ?>
                </div><!-- .started_at -->
                <div class="log-count">
                  <strong>記録回数</strong>　120回
                </div><!-- .log_count -->
              </div><!-- .user_info -->
            </li>
            <?php endforeach; ?>
            </ul>
          </div><!-- #member -->
          
        </div><!-- .tab-content -->
      </div><!-- #graph-content -->
      <script>
        $('#tab-menu a').click(function (e) {
          e.preventDefault();
          $(this).tab('show');
        });
      </script>      
    </div><!-- #graph -->

    <div id="second" class="clearfix">
      <div id="left">
        <div id="gtop-head">
          <div class="sub-head-image">
            <img src="/html/imgs/crown.png" height="30px" width="30px"/>
          </div><!-- .sub-head-image -->
          <div class="sub-head-title">
            <h4 class="sub-header">グループメンバーの学習目標を見る</h4>
          </div><!-- .sub-head-title -->
        </div><!-- #goal-head -->
        <div id="member-goal">         
          <div id="gtop-inner-wrap">
            <ul class="unstyled">
            <?php if($member_goals):?>          
              <?php foreach($member_goals as $data):?>
              <li class="fri-goal-list clearfix">
                <div class="fri-image">
                  <img src="<?php echo $data->picture ?>" />
                </div><!-- .fri-image -->
                <div class="fri-content">
                  <h4><?php echo $data->fb_name ?></h4>
                  <p><?php echo $data->goal ?></p>
                  <p><?php echo arrange_date($data->period) ?>まで</p>
                  <p>あと<span class="red"><?php echo $data->diff ?>日</span>です</p>
                </div>
              </li><!-- .fri-goal-list -->
              <?php endforeach;?>
          <?php endif;?>
              </ul>
              <!--
            <ul class="unstyled">
              <li class="clearfix gtop-user-data">
                <h3><img src="/html/imgs/king.png" height="25px" width="25px" />グループで一番の努力家<h3/>
                  <div class="gtop-user-photo">
                    <img src="https://graph.facebook.com/<?php //echo $hard_worker->fb_id ?>/picture" height="50px" width="50px" />
                  </div>
                  <div class="gtop-user-name">
                    <h4><?php //echo $hard_worker->fb_name ?>さん</h4>
                    <p>グループ『hoge』で総学習時間がナンバーワンの人物</p>
                  </div>
              </li>
              <li class="clearfix gtop-user-data">
                <h3><img src="/html/imgs/king.png" height="25px" width="25px" />グループで一番の安定感<h3/>
                  <div class="gtop-user-photo">
                    <img src="https://graph.facebook.com/<?php //echo $stability->fb_id ?>/picture" height="50px" width="50px" />
                  </div>
                  <div class="gtop-user-name">
                    <h4><?php //echo $stability->fb_name ?>さん</h4>
                    <p>グループ『hoge』で平均学習時間がナンバーワンの人物</p>
                  </div>
              </li>
              <li class="clearfix gtop-user-data">
                <h3><img src="/html/imgs/king.png" height="25px" width="25px" />グループで一番の学習満足度<h3/>
                  <div class="gtop-user-photo">
                    <img src="https://graph.facebook.com/<?php //echo $satisfaction->fb_id ?>/picture" height="50px" width="50px" />
                  </div>
                  <div class="gtop-user-name">
                    <h4><?php //echo $satisfaction->fb_name ?>さん</h4>
                    <p>グループ『hoge』で平均自己評価がナンバーワンの人物</p>
                  </div>
              </li>
              <li class="clearfix gtop-user-data">
                <h3><img src="/html/imgs/king.png" height="25px" width="25px" />グループで一番の計画性<h3/>
                  <div class="gtop-user-photo">
                    <img src="https://graph.facebook.com/100003818608966/picture" height="50px" width="50px" />
                  </div>
                  <div class="gtop-user-name">
                    <h4>Hiroki Sanpeiさん</h4>
                    <p>グループ『hoge』で学習計画達成率がナンバーワンの人物</p>
                  </div>
              </li>

            </ul>
            -->
            
          </div><!-- #gtop-inner-wrap -->
        </div><!-- #goal -->
      </div><!-- #left -->
      
      
      <div id="right">
        <div id="recent-log-head">
          <div class="sub-head-image">
            <img src="/html/imgs/people.png" height="30px" width="30px"/>
          </div><!-- .sub-head-image -->
          <div class="sub-head-title">
            <h4 class="sub-header">グループ『<?php echo $groupinfo['name'] ?>』のウォールを見る</h4>
          </div><!-- .sub-head-title -->
        </div><!-- #recent-log-head -->
          
        <div id="recent-log">
          <div id="log-wrap">
            <div id="live-wall"></div>
          </div>         
        </div><!-- #recent-log -->
          
      </div><!-- #right -->
    </div><!-- #second -->
        

  
  <!--フッター-->
  <?php echo $footer ?>
  <input id="group_id" type="hidden" value="<?php echo $groupinfo['id'] ?>" />
  <input id="fb_id" type="hidden" value="<?php echo $fb_id ?>" />

  </div><!--=== .container ===-->  
  <script type="text/javascript" src="/html/js/bootstrap.min.js"></script>
</body>
</html>