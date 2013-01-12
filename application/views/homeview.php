<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8" />
  <link rel="stylesheet" type="text/css" href="/html/css/bootstrap.css" />
  <link rel="stylesheet" type="text/css" href="/html/css/jquery_notification.css" />
  <link rel="stylesheet" type="text/css" href="/html/css/common.css" />
  <link rel="stylesheet" type="text/css" href="/html/css/home.css" /> 
  
  <script type="text/javascript" src="/html/js/jquery.js"></script>
  <script type="text/javascript" src="/html/js/facebook.js"></script>
  <script type="text/javascript" src="/html/js/logout.js"></script>
  <script type="text/javascript" src="/html/js/jquery_notification.js"></script>
  <script type="text/javascript" src="/html/js/scrolltop.js"></script>
  <script type="text/javascript" src="/html/js/home.js"></script>
  <title>トップページ</title>
</head>
<body>
  
  <!--Facebook SDK-->
  <div id="fb-root"></div>
  
  <!--=======================ナビゲーションバー=======================-->
　<?php echo $header ?>
 
  <!--=======================コンテンツ本体=======================-->
  <div class="container" style="padding:50px;">    
    <div id="test"></div>
    <!--Main Contents-->
    <div id="log-head" class="clearfix">
      <div class="sub-head-image">
        <img src="/html/imgs/pen2.png" height="30px" width="30px"/>
      </div><!-- .sub-head-image -->
      <div class="sub-head-title">
        <h4 class="sub-header"><?php echo date('Y年n月d日') ?>の学習を記録する</h4>
      </div><!-- .sub-head-title -->
    </div><!-- #graph-head -->
    <div id="log-box">
      <div id="log-inner" class="clearfix">
        <?php if($is_log != 0):?>
          <p><strong><?php echo date('Y年n月d日') ?>の学習は既に記録しています。修正をする場合は<a href="/log/date/">記録専用ページ</a>へ。</strong></p><br/>
        <?php endif;?>
        <form name="log" action="/home/submit_log2/<?php echo $date ?>" method="post" onSubmit="return check()">
          <div id="log-left">
            <p><i class="icon-time"></i> 学習内容と学習時間</p>
              <input class="span4" type="text" name="fl_content" maxlength="30" placeholder="(例) TOEICの勉強" style="height:27px;" required <?php if($is_log != 0):?>disabled<?php endif;?>/>
              <input class="span1" type="number" name="fl_time" max="1440" min="0" onChange="sum()" placeholder="120" style="height:27px;" required <?php if($is_log != 0):?>disabled<?php endif;?>/> 分
              <div class="down-box">
                <p><i class="icon-comment"></i> コメントや感想</p>
                <textarea class="span5" name="comment" maxlength="500" cols="47" rows="3" placeholder="学習の感想、新しく得た知識、グループのみんなに伝えたいことなどを自由に記述して下さい" required <?php if($is_log != 0):?>disabled<?php endif;?>></textarea> 
                <br />
                <a href="/log/date/<?php echo $date ?>">&gt;&gt;もっと細かく記録する・過去の学習を記録する</a>
              </div>
          </div>
          
          <div id="log-right">
            <p><i class="icon-thumbs-up"></i> 学習の満足度</p>
              <div class="radio-group clearfix" style="margin-bottom:25px;">                
                <div>
                  <input id="Radio1" class="radio" type="radio" name="selfreview" value="5" required <?php if($is_log != 0):?>disabled<?php endif;?>/>
                  <label for="Radio1">
                    <img class="tip" title="今日は十分頑張った！(5point)" src="/html/imgs/grin.png"/>
                  </label>
                </div>
                <div>
                  <input id="Radio2" class="radio" type="radio" name="selfreview" value="4" required <?php if($is_log != 0):?>disabled<?php endif;?>/>
                  <label for="Radio2">
                    <img class="tip" title="7,8割くらいの力は出せたかな(4point)" src="/html/imgs/smile.png"/>
                  </label>
                </div>
                <div>
                  <input id="Radio3" class="radio" type="radio" name="selfreview" value="3" required <?php if($is_log != 0):?>disabled<?php endif;?>/>
                  <label for="Radio3">
                    <img class="tip" title="可もなく不可もなく、まずまずです(3point)" src="/html/imgs/plain.png"/>
                  </label>
                </div>
                <div>
                  <input id="Radio4" class="radio" type="radio" name="selfreview" value="2" required <?php if($is_log != 0):?>disabled<?php endif;?>/>
                  <label for="Radio4">
                    <img class="tip" title="もう少し頑張れたなぁ(2point)" src="/html/imgs/sad.png"/>
                  </label>
                </div>
                <div>
                  <input id="Radio5" class="radio" type="radio" name="selfreview" value="1" required <?php if($is_log != 0):?>disabled<?php endif;?>/>
                  <label for="Radio5">
                    <img class="tip" title="死んだ....(1point)" src="/html/imgs/crying.png"/>
                  </label>
                </div>
              </div><!-- .radio-group -->
            <p><i class="icon-share"></i> 共有するグループの選択</p>
            <?php if($groupinfo):?>
              <select id="gid" class="span4" name="group" <?php if($is_log != 0):?>disabled<?php endif;?>>
                <?php foreach($groupinfo as $data):?>
                  <option value="<?php echo $data->group_id ?>"><?php echo $data->group_name ?></option>
                <?php endforeach;?>
              </select>
            <?php endif;?>
            <?php if($is_log == 0):?>
              <input id="submit-btn" class="btn btn-large btn-primary" type="submit" name="log_button" value="この内容で記録する!!" />
              <input class="btn btn-danger btn-large" type="reset" value="リセットする" />
            <?php else:?>
              <input id="submit-btn" class="btn btn-large btn-primary" type="submit" value="この内容で記録する!!" disabled/>
              <input class="btn btn-danger btn-large" type="reset" value="リセットする" disabled/>              
            <?php endif;?>
          </div>
        </form>
      </div>
    </div>
    
    <!--
    <div id="main-box" class="clearfix">
      
      <ul id="cont-list" class="image-overlay" style="margin:0;">
    
        <li class="content-box item">
          <a href="/group/" >
            <img alt="グループの記録・情報" src="/html/imgs/group2.png"/>
          </a>
            <div class="g_caption">
              <h3>グループの学習記録を見る</h3>
              <p>活動しているグループ一覧</p>
              <?php //if ($groups): ?>
                <ul>
                  <?php //foreach ($groups as $group): ?>
                    <li><a href="/group/view/<?php //echo $group->group_id ?>"><?php //echo $group->group_name ?></a></li>
                  <?php //endforeach; ?>
                </ul>
              <?php //else:?>
                <p><span class="websymbols" style="color:red;">W</span> まだ活動しているグループはありません</p>
              <?php //endif; ?>
            </div>
        </li>
        
        <li class="content-box">
          <a href="/log/date/<?php //echo date('Ymd')?>">
            <img alt="記録をする" src="/html/imgs/record.png" />
            <div class="caption" style="margin-top:-2px;">
              <h3>記録をする</h3>
              <p>今日は<?php //echo arrange_date($today) ?>です<p>
            </div>
          </a>
        </li>
        
        <li class="content-box">
          <a href="/user/">
            <img alt="自分の学習記録" src="/html/imgs/user.png" />
            <div class="caption" style="margin-top:-2px;">
              <h3>自分の学習記録を見る</h3>
              <p>click for more info</p>
            </div>
          </a>
        </li>
        
      </ul>
       
    </div>
    -->
    
    <!--Sub Contents-->
    <div class="clearfix" style="width:100%;">
    
      <!--Sub Left Contents-->
      <div id="sub-left">
        
          <div class="sub-head clearfix"><!--サブコンテンツヘッダ-->
            <div class="sub-head-image">
              <img src="/html/imgs/flag2.png" height="30px" width="30px"/>
            </div><!-- .sub-head-image -->
            <div class="sub-head-title">
              <h4 class="sub-header">みんなの目標と自分の目標をチェックする <a style="cursor:pointer;" title="あなたが学習を共有しているグループ全ての仲間たちの学習目標を表示">[?]</a></h4>
            </div><!-- .sub-head-title -->            
          </div><!--div class="sub-head"-->
        
        <div id="goal-box">            
          
          <div id="my-goal">
            <h4><i class="icon-user" style="margin-top:1px;"></i> <?php if(strlen($fb_name)<25){echo $fb_name;}else{echo strstr($fb_name, ' ', true);}?>さんの学習目標</h4>
              <?php if($goals):?>
                <div class="goal-list clearfix">
                  <dl style="margin:0;">
                  <?php foreach($goals as $goal) :?>
                      <dt class="goal-name"><?php echo $goal->goal; ?></dt>
                      <dd class="goal-period"><?php echo "{$goal->period}まで";?></dd>
                      <div class="clear"></div>
                  <?php endforeach;?>
                  </dl>
                </div><!-- .goal-list -->
              <?php else:?>
                <div class="goal-list">
                  <p><span class="websymbols" style="color:red;">W</span> まだ学習目標を登録していません</p>
                  <p> <a href="/user/">ユーザー専用ページ</a>の学習目標タブから学習目標を設定しよう</p>
                </div><!-- .goal-list -->
              <?php endif;?>
          </div><!--div id="my-goal"-->
          
          <?php if($fri_goal):?>          
            <div id="friend-goal">
              <div id="goal-border"></div>
              <?php foreach($fri_goal as $data):?>
              <div class="fri-goal-list clearfix">
                <div class="fri-image">
                  <img src="https://graph.facebook.com/<?php echo $data->fb_id ?>/picture" />
                </div><!-- .fri-image -->
                <span><?php echo $data->fb_name ?></span>
                <dl style="margin:0;">
                  <dt class="fri-goal-name"><?php echo $data->goal ?></dt>
                  <dd class="fri-goal-period"><?php echo $data->period ?>まで</dd>
                  <div class="clear"></div>
                </dl>
              </div><!-- .fri-goal-list -->
              <?php endforeach;?>
            </div>
          <?php endif;?>
        </div><!-- .goal-box -->

        
        <!--
        <div id="realtime-head" class="sub-head">
          <div class="sub-head-image">
            <img src="/html/imgs/share2.png" width="30px" height="30px"/>
          </div>
          <div class="sub-head-title">
            <h4 class="sub-header">リアルな学習状況をみんなに伝える</h4>
          </div>
          <div class="clear"></div>
        </div>
      
        <div id="realtime-share">                    
          <div id="sel-group">
            <p>グループを選択してボタンをクリックすると、選択したグループのウォールに学習状況が投稿されます</p>
            <h4>どのグループに伝えますか？</h4>
            <?php //if($groupinfo):?>
              <select id="gid" class="span3">
                <?php //foreach($groupinfo['data'] as $group):?>
                  <option value="<?php //echo $group['id'] ?>"><?php //echo $group['name'] ?></option>
                <?php //endforeach;?>
              </select>
            <?php //else:?>
              <p><span class="websymbols" style="color:red;">W</span> 共有するグループがありません</p>
            <?php //endif;?>
          </div>
          
          <div id="buttons">
            <h4>何を伝えますか？</h4>
            <button class="btn btn-primary minishare" id="btn1" value="1">今からはじめる<br/>(`・ω・´)</button>
            <button class="btn btn-success minishare" value="2">休憩するよ<br/>(・ω・;)</button>
            <button class="btn btn-inverse minishare" value="3">今日は終わり<br/>( ´Д｀)=3</button>
            <button class="btn btn-danger minishare" value="4">しんどいよ〜<br/>(((ﾟДﾟ)))</button>
            <button class="btn btn-warning minishare" value="5">今から本気だす<br/>(# ﾟДﾟ)</button>
            <button class="btn minishare" value="6">明日から本気だす<br/>(A` )</button>
          </div>          
        </div>
        -->

        
      </div><!--== #sub-left ==-->
    
      
      <!--Sub Right Contents-->      
      <div id="sub-right">
        <div class="sub-head2 clearfix">
          <div class="sub-head-image2">
            <img src="/html/imgs/search.png" height="30px" width="30px"/>
          </div><!-- .sub-head-image2 -->
          <div class="sub-head-title2">
            <h4 class="sub-header">最近のみんなの学習状況を見る <a style="cursor:pointer;"title="あなたが学習を共有しているグループ全ての仲間たちの最新学習状況を表示">[?]</a></h4>
          </div><!-- .sub-head-title2 -->
        </div><!-- .sub-head2 -->
        <div id="recent-act">
                   
         <div id="log-wrap">
            <ul class="unstyled">
            <?php if($all_rec):?>
            <?php foreach($all_rec as $data): ?>
            <li class="user-data clearfix">
              <div class="user-photo">
                <img src="https://graph.facebook.com/<?php echo $data->fb_id ?>/picture" height="50px" width="50px" />
              </div>
              <div class="log-data">
                <p><span class="bold blue"><?php echo $data->fb_name ?></span>さんの<?php echo arrange_date($data->register_at) ?>の学習記録です</p>
                <p>
                  <strong>学習内容</strong>：<?php echo $data->fl_content ?>
                  <?php if ($data->sl_content): ?>
                    <?php echo "、{$data->sl_content}" ?>
                  <?php endif; ?>
                  <?php if ($data->tl_content): ?>
                    <?php echo "、{$data->tl_content}" ?>
                  <?php endif; ?>
                </p>
                <p><strong>学習時間</strong>：<?php echo $data->total_time ?>分</p>
                <p><strong>コメント</strong>：<?php echo $data->comment ?></p>                
              </div><!-- .log-data -->
            </li>
            <?php endforeach;?>
            <?php else:?>
            <p class="red"><span class="websymbols" style="color:red;">W</span> まだ一度も学習を記録していないため、閲覧できる学習データがありません</p>
            <p>まずは一度、学習の記録をしましょう。学習の共有をしたグループの学習状況が閲覧できるようになります。</p>
            <a href="/log/date/">&gt;&gt; 学習記録ページに行く</a>
            <?php endif;?>
            </ul>
          </div><!-- #log-wrap -->             
          
          
          
          
          
          
        </div>
      </div><!--== #sub-right ==-->
    
    </div><!--== Close Sub Contents ==-->

    
    <!--フッター-->
    <?php echo $footer ?>
    <input type="hidden" value="<?php echo $fb_name ?>" id="fb_name" />  
    <input type="hidden" value="<?php echo $fb_id ?>" id="fb_id" />
  </div><!--=== .container ===-->
  
  <script type="text/javascript" src="/html/js/bootstrap.min.js"></script>
</body>
</html>