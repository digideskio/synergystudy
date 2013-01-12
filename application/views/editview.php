<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8" />
  <title>修正ページ</title>
  <link rel="stylesheet" type="text/css" href="/html/css/bootstrap.css" />
  <link rel="stylesheet" type="text/css" href="/html/css/jquery_notification.css" />
  <link rel="stylesheet" type="text/css" href="/html/css/calendar.css" />
  <link rel="stylesheet" type="text/css" href="/html/css/common.css" />
  <link rel="stylesheet" type="text/css" href="/html/css/log.css" />  
  
  <script type="text/javascript" src="/html/js/jquery.js"></script>
  <script type="text/javascript" src="/html/js/facebook.js"></script>
  <script type="text/javascript" src="/html/js/logout.js"></script>
  <script type="text/javascript" src="/html/js/scrolltop.js"></script>
  <script type="text/javascript" src="/html/js/autocalc.js"></script>
  <script type="text/javascript" src="/html/js/jquery_notification.js"></script>
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
        <h2><span class="iconfonts" style="vertical-align:top;">{</span> <?php echo arrange_date($date2) ?>の学習を修正する</h2>

        <form class="form-horizontal" name="log" action="../edit_log/<?php echo $date ?>" method="post" onSubmit="return check()">
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
                  <td><input class="span3" type="text" name="fl_content" maxlength="30" placeholder="(例) プログラミング" value="<?php echo set_value('fl_content',$log->fl_content) ?>" required /></td>
                  <td><input class="span1" type="number" name="fl_time" max="1440" min="0" placeholder="120" value="<?php echo set_value('fl_time',$log->fl_time) ?>" onChange="sum()" required /> 分</td>
                </tr>
                <tr>
                  <td><input class="span3" type="text" name="sl_content" maxlength="30" placeholder="(例) TOEIC" value="<?php echo ($log->sl_content) ? set_value('sl_content',$log->sl_content): ""; ?>" /></td>
                  <td><input class="span1" type="number" name="sl_time" max="1440" min="0" placeholder="90" value="<?php echo ($log->sl_time != 0) ? set_value('sl_time',$log->sl_time): ""; ?>" onChange="sum()" /> 分</td>
                </tr>
                <tr>
                  <td><input class="span3" type="text" name="tl_content" maxlength="30" value="<?php echo ($log->tl_content) ? set_value('tl_content',$log->tl_content): ""; ?>" /></td>
                  <td><input class="span1" type="number" name="tl_time" max="1440" min="0" value="<?php echo ($log->tl_time != 0) ? set_value('tl_time',$log->tl_time): ""; ?>" onChange="sum()" /> 分</td>
                </tr>
                <tr>
                  <td class="total">合計学習時間</td>
                  <td><input class="span1" type="text" name="total_time" max="1440" min="0" value="<?php echo set_value('total_time',$log->total_time) ?>" readonly /> 分</td>
                </tr>
              </table>
            </div>
          </div><!-- .control-group -->
          
          <!-- コメントや感想 -->
          <div class="control-group">
            <label class="control-label" for="input01"><i class="icon-comment"></i> コメントや感想</label>
            <div class="controls">
              <textarea class="span4" name="comment" maxlength="500" cols="47" rows="3" placeholder="学習の感想、新しく得た知識、グループのみんなに伝えたいことなどを自由に記述して下さい" required ><?php echo set_value('comment',$log2->comment) ?></textarea>
            </div>
          </div><!-- .control-group -->
                  
          <!-- 満足度・自己評価 -->
          <div class="control-group">
            <label class="control-label" for="input01"><!--<span class="websymbols">R</span>--><i class="icon-thumbs-up"></i> 学習の満足度</label>
            <div class="controls">
              <div class="radio-group clearfix">
                
                <div>
                  <input id="Radio1" class="radio" type="radio" name="selfreview" value="5" <?php if($log2->selfreview == "5"):?>checked<?php endif;?> required />
                  <label for="Radio1">
                    <img class="tip" title="今日は十分頑張った！(5point)" src="/html/imgs/grin.png"/>
                  </label>
                </div>
                
                <div>
                  <input id="Radio2" class="radio" type="radio" name="selfreview" value="4" <?php if($log2->selfreview == "4"):?>checked<?php endif;?> required />
                  <label for="Radio2">
                    <img class="tip" title="7,8割くらいの力は出せたかな(4point)" src="/html/imgs/smile.png"/>
                  </label>
                </div>
                
                <div>
                  <input id="Radio3" class="radio" type="radio" name="selfreview" value="3" <?php if($log2->selfreview == "3"):?>checked<?php endif;?> required />
                  <label for="Radio3">
                    <img class="tip" title="可もなく不可もなく、まずまずです(3point)" src="/html/imgs/plain.png"/>
                  </label>
                </div>

                <div>
                  <input id="Radio4" class="radio" type="radio" name="selfreview" value="2" <?php if($log2->selfreview == "2"):?>checked<?php endif;?> required />
                  <label for="Radio4">
                    <img class="tip" title="もう少し頑張れたなぁ(2point)" src="/html/imgs/sad.png"/>
                  </label>
                </div>

                <div>
                  <input id="Radio5" class="radio" type="radio" name="selfreview" value="1" <?php if($log2->selfreview == "1"):?>checked<?php endif;?> required />
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
              <textarea class="span4" name="nextplan" maxlength="250" cols="47" rows="2" placeholder="学習予定日、学習内容、ノルマなど" required ><?php echo $log2->nextplan ?></textarea>
            </div>
          </div>
          -->   
          
          
          <div class="control-group">
            <label class="control-label" for="input01"><i class="icon-share"></i> 共有するグループ</label>
            <div class="controls">
              
              <!--セレクト-->              
              <select id="gid" class="span3" name="group[]">
                <?php foreach($groupinfo['data'] as $group):?>
                  <option value="<?php echo $group['id'] ?>" <?php if(in_array($group['id'],$groupids)):?>selected<?php endif;?>><?php echo $group['name'] ?></option>
                <?php endforeach;?>
              </select>
              
              <!--チェック-->
              <?php //foreach($groupinfo['data'] as $group): ?>
              <!--
                <label class="checkbox">
                  <input type="checkbox" name="group[]" value="<?php //echo $group['id'] ?>" <?php //if(in_array($group['id'],$groupids)):?>checked<?php //endif;?> /><?php //echo $group['name'];?>
                </label>
              <?php //endforeach; ?>
              -->
            </div><!-- .controls -->
          </div><!-- .control-group -->          
          
          <input id="submit-btn" class="btn btn-success" type="submit" name="log_button" value="この内容で修正する!!" />
          <input class="btn btn-danger" type="reset" value="リセットする" />

          </fieldset>
        </form>
      </div><!--== #main ==-->
      
    </div><!--=== #wrapper ===-->
    <!--フッター-->
    <?php echo $footer ?>
      
  </div><!-- .container -->
  <script type="text/javascript" src="/html/js/bootstrap.min.js"></script>
</body>
</html>

