<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8" />
  <title>記録ページ</title>
  <link rel="stylesheet" type="text/css" href="/html/css/bootstrap.css" />
  <link rel="stylesheet" type="text/css" href="/html/css/calendar.css" />
  <link rel="stylesheet" type="text/css" href="/html/css/common.css" />
  <link rel="stylesheet" type="text/css" href="/html/css/log.css" />

  <script type="text/javascript" src="/html/js/jquery.js"></script>
  <script type="text/javascript" src="/html/js/facebook.js"></script>
  <script type="text/javascript" src="/html/js/logout.js"></script>
  <script type="text/javascript" src="/html/js/scrolltop.js"></script>
  <script type="text/javascript" src="/html/js/autocalc.js"></script>
  <script type="text/javascript" src="/html/js/log.js"></script>
</head>
<body>
  <!--Facebook SDK-->
  <div id="fb-root"></div>
  
  <!--=======================ナビゲーションバー=======================-->
　<?php echo $header ?>
 
  <!--=======================コンテンツ=======================-->
  <div class="container clearfix" style="padding:50px;"> 
    
    <div id="wrapper" class="clearfix">

      <!--== Sub Contents==-->
      <div id="sub">
        <?php echo $calendar ?>        
      </div><!--== #sub ==-->
      
      <!--== Main Contents==-->
      <div id="main">
        <h2><span class="iconfonts" style="vertical-align:top;">{</span> <?php echo arrange_date($date2) ?>の学習を記録する</h2>
                
        <form class="form-horizontal" name="log" action="../submit_log/<?php echo $date ?>" method="post" onSubmit="return check()">
          <fieldset style="color:#696969;">
            <?php if(isset($errors)):?>
            <div class="errors_display">
              <?php if(isset($errors['fl_content_blank'])):?>
                <p><span class="websymbols">W</span> 最上部の学習内容の入力は必須です</p>
              <?php endif;?>
              <?php if(isset($errors['fl_content_over']) || isset($errors['sl_content_over']) ||isset($errors['tl_content_over'])):?>
                <p><span class="websymbols">W</span> 学習内容は30文字以内で入力して下さい</p>
              <?php endif;?>
              <?php if(isset($errors['fl_time_blank'])):?>
                <p><span class="websymbols">W</span> 最上部の学習時間の入力は必須です</p>
              <?php endif;?>
              <?php if(isset($errors['fl_time_over']) || isset($errors['fl_time_str_over'])|| isset($errors['sl_time_over']) || isset($errors['sl_time_str_over']) || isset($errors['tl_time_over']) || isset($errors['tl_time_str_over']) || isset($errors['total_time_over']) || isset($errors['total_time_str_over'])):?>
                <p><span class="websymbols">W</span> 入力された学習時間が不正です</p>
              <?php endif;?>
              <?php if(isset($errors['fl_time_hw']) || isset($errors['sl_time_hw']) || isset($errors['tl_time_hw']) || isset($errors['total_time_hw'])):?>
                <p><span class="websymbols">W</span> 学習時間は半角数字で入力して下さい</p>
              <?php endif;?>
              <?php if(isset($errors['total_time_blank'])):?>
                <p><span class="websymbols">W</span> 合計学習時間の入力は必須です</p>
              <?php endif;?>
              <?php if(isset($errors['comment_blank'])):?>
                <p><span class="websymbols">W</span> コメントの入力は必須です</p>
              <?php endif;?>
              <?php if(isset($errors['comment_over'])):?>
                <p><span class="websymbols">W</span> コメントは500文字以内で入力して下さい</p>
              <?php endif;?>
              <?php if(isset($errors['selfreview_blank'])):?>
                <p><span class="websymbols">W</span> 自己評価の入力は必須です</p>
              <?php endif;?>
              <?php if(isset($errors['selfreview_value']) || isset($errors['selfreview_str_value'])):?>
                <p><span class="websymbols">W</span> 自己評価の値が不正です</p>
              <?php endif;?>
              <?php if(isset($errors['selfreview_hw'])):?>
                <p><span class="websymbols">W</span> 自己評価の値は半角数字であるはずです</p>
              <?php endif;?>
              <?php if(isset($errors['group_id_blank'])):?>
                <p><span class="websymbols">W</span> グループが選択されていません</p>
              <?php endif;?>
              <?php if(isset($errors['group_id_error'])):?>
                <p><span class="websymbols">W</span> 選択されたグループが不正です</p>
              <?php endif;?>
            </div>
            <?php endif;?>
          <!--学習内容と学習時間-->
          <div class="control-group">
            <label class="control-label" for="input01"><i class="icon-time"></i> 学習内容と学習時間</label>
            <div class="controls">
              <table>
                <tr>
                  <td><input class="span3" type="text" name="fl_content" maxlength="30" placeholder="(例) プログラミング" value="<?php echo set_value('fl_content');?>" required /></td>
                  <td><input class="span1" type="number" name="fl_time" max="1440" min="0" onChange="sum()" placeholder="120" value="<?php echo set_value('fl_time');?>" required /> 分</td>
                </tr>
                <tr>
                  <td><input class="span3" type="text" name="sl_content" maxlength="30" placeholder="(例) TOEIC" value="<?php echo set_value('sl_content');?>" /></td>
                  <td><input class="span1" type="number" name="sl_time" max="1440" min="0" placeholder="90" onChange="sum()" value="<?php echo set_value('sl_time');?>" /> 分</td>
                </tr>
                <tr>
                  <td><input class="span3" type="text" name="tl_content" maxlength="30" value="<?php echo set_value('tl_content');?>"/></td>
                  <td><input class="span1" type="number" name="tl_time" max="1440" min="0" onChange="sum()" value="<?php echo set_value('tl_time');?>" /> 分</td>
                </tr>
                <tr>
                  <td class="total">合計学習時間</td>
                  <td><input class="span1" type="text" name="total_time" max="1440" min="0" value="<?php echo set_value('total_time');?>" readonly /> 分</td>
                </tr>
              </table>
            </div>
          </div><!-- .control-group -->
          
          <!-- コメントや感想 -->
          <div class="control-group">
            <label class="control-label" for="input01"><i class="icon-comment"></i> コメントや感想</label>
            <div class="controls">
              <textarea class="span4" name="comment" maxlength="500" cols="47" rows="3" placeholder="学習の感想、新しく得た知識、グループのみんなに伝えたいことなどを自由に記述して下さい" required ><?php echo set_value('comment');?></textarea>
            </div>
          </div><!-- .control-group -->
                  
          <!-- 満足度・自己評価 -->
          <div class="control-group">
            <label class="control-label" for="input01"><i class="icon-thumbs-up"></i> 学習の満足度</label>
            <div class="controls">
              <div class="radio-group clearfix">
                
                <div>
                  <input id="Radio1" class="radio" type="radio" name="selfreview" value="5" <?php echo set_radio('selfreview','5'); ?> required />
                  <label for="Radio1">
                    <img class="tip" title="今日は十分頑張った！(5point)" src="/html/imgs/grin.png"/>
                  </label>
                </div>
                
                <div>
                  <input id="Radio2" class="radio" type="radio" name="selfreview" value="4" <?php echo set_radio('selfreview','4'); ?> required />
                  <label for="Radio2">
                    <img class="tip" title="7,8割くらいの力は出せたかな(4point)" src="/html/imgs/smile.png"/>
                  </label>
                </div>
                
                <div>
                  <input id="Radio3" class="radio" type="radio" name="selfreview" value="3" <?php echo set_radio('selfreview','3'); ?> required />
                  <label for="Radio3">
                    <img class="tip" title="可もなく不可もなく、まずまずです(3point)" src="/html/imgs/plain.png"/>
                  </label>
                </div>

                <div>
                  <input id="Radio4" class="radio" type="radio" name="selfreview" value="2" <?php echo set_radio('selfreview','2'); ?> required />
                  <label for="Radio4">
                    <img class="tip" title="もう少し頑張れたなぁ(2point)" src="/html/imgs/sad.png"/>
                  </label>
                </div>

                <div>
                  <input id="Radio5" class="radio" type="radio" name="selfreview" value="1" <?php echo set_radio('selfreview','1'); ?> required />
                  <label for="Radio5">
                    <img class="tip" title="死んだ....(1point)" src="/html/imgs/crying.png"/>
                  </label>
                </div>
                
              </div><!-- .radio-group -->
            </div><!-- .controls -->
          </div><!-- .control-group -->
          
          <!-- 学習計画 -->
          <!--
          <div class="control-group">
            <label class="control-label" for="input01"><i class="icon-tasks"></i> 次回の学習計画</label>
            <div class="controls">
              <textarea class="span4" name="nextplan" maxlength="250" cols="47" rows="2" placeholder="学習予定日、学習内容、ノルマなど" required ></textarea>
            </div>
          </div>
          -->          
          
          <div class="control-group">
            <label class="control-label" for="input01"><i class="icon-share"></i> 共有するグループ</label>
            <div class="controls">
              
              <!--セレクト-->
              <?php if($groupinfo['data']):?>
              <select id="gid" class="span3" name="group[]">
                <?php foreach($groupinfo['data'] as $group):?>
                  <option value="<?php echo $group['id'] ?>"><?php echo $group['name'] ?></option>
                <?php endforeach;?>
              </select>
              <?php else:?>
              <p class="error"><span class="websymbols error">W</span>  所属するグループがひとつもありません。<br/>Facebookでグループの作成をしてから記録をして下さい。</p>         
              <?php endif;?>
              
              <!--チェック-->
              <?php //if($groupinfo['data']):?>
              <?php //foreach ($groupinfo['data'] as $group): ?>
              <!--
                <label class="checkbox">
                  <input type="checkbox" name="group[]" value="<?php //echo $group['id'] ?>" <?php //echo set_checkbox("group[]","{$group['id']}"); ?>/><?php //echo $group['name']; ?>
                </label>
              -->
              <?php //endforeach; ?>
              <?php //else:?>
              <!--
              <p class="error"><span class="websymbols error">W</span>  所属するグループがひとつもありません。<br/>Facebookでグループの作成をしてから記録をして下さい。</p>         
              -->
              <?php //endif;?>
              
              
            </div><!-- .controls -->
          </div><!-- .control-group -->
          
          <input id="submit-btn" class="btn btn-primary" type="submit" name="log_button" value="この内容で記録する!!" />
          <input class="btn btn-danger" type="reset" value="リセットする" />

          </fieldset>
        </form>
      </div><!--== #main ==-->
      
    </div><!--=== #wrapper ===-->
    <!--フッター-->
    <?php echo $footer ?>
  </div><!--===== .container =====-->
  
  <script type="text/javascript" src="/html/js/bootstrap.min.js"></script>
</body>
</html>

