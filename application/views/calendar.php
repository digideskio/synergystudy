<?php
//指定日
if(preg_match('/^[0-9]{8}/',$date)){
  $yr = substr($date,0,4);
  $mon = substr($date,4,2);
  $dy = substr($date,6,2);
  $today = getdate(mktime(0,0,0,$mon,$dy,$yr));
}else{
  $today = getdate();
}

//月の初日・曜日のデータ・翌月または前月のデータを取得するために使う変数群
$m_num = $today["mon"]; //月
$d_num =$today["mday"]; //日
$year = $today["year"]; //年
$hiduke = "{$year}年{$m_num}月{$d_num}日";

//月の初日の曜日
$f_today = getdate(mktime(0,0,0,$m_num,1,$year));
$wday = $f_today["wday"]; //0~6がはいっている。曜日。

//月の名称
$m_name = $today["month"]." $year";
//前月のデータ -「20111123」のような値
$prev_month = date("Ymd",mktime(0,0,0,$m_num,0,$year));
//翌月のデータ -「20120123」のような値
$next_month = date("Ymd",mktime(0,0,0,$m_num+1,1,$year));
//前日のデータ -「20111222」のような値
$prev_day = date("Ymd",mktime(0,0,0,$m_num,$d_num-1,$year));
//翌日のデータ -「20111224」のような値
$next_day = date("Ymd",mktime(0,0,0,$m_num,$d_num+1,$year));
?>
  <div id="calendar">
    <table cellspacing=0>
      <tr>
        <td class="calendar-top radius-left"><a href="<?php echo $prev_month;?>">&lt;&lt;</a></td>
        <td class="calendar-top calendar-big" colspan=5><?php echo $m_name;?></td>
        <td class="calendar-top radius-right"><a href="<?php echo $next_month;?>">&gt;&gt;</a></td>
      </tr>
      <tr>
        <td class="calendar">Sun</td>       
        <td class="calendar">Mon</td>
        <td class="calendar">Tue</td>
        <td class="calendar">Wed</td>
        <td class="calendar">Thr</td>
        <td class="calendar">Fri</td>
        <td class="calendar">Stu</td>
      </tr>            
      <tr>
      <?php
        //カレンダーの最初の空白部分を作る
        for($i=0; $i<$wday; $i++){
          echo "<td class=\"usual\">　</td>\n";
        }
        //カレンダーの日付
        $day = 1;
        while(checkdate($m_num,$day,$year)){
          
          $ca_link = sprintf("%4d%02d%02d",$year,$m_num,$day);

          //当日どうかのチェック。当日だったら処理を変える
          if(($day == $today["mday"]) && ($m_num == $today["mon"]) && ($year == $today["year"])){
            //当日の処理
            echo "<td class=\"today calendar\"><a class=\"today calendar\" href=\"http://synergystudy.me/log/date/{$ca_link}\">$day</a></td>\n";
            
          }elseif($wday == 0 ){
            //日曜日の処理
            echo"<td class=\"sunday calendar\"><a class=\"sunday calendar\" href=\"http://synergystudy.me/log/date/{$ca_link}\">$day</a></td>\n";
            
          }elseif($wday ==6){
            //土曜日の処理
            echo "<td class=\"saturday calendar\"><a class=\"saturday calendar\" href=\"http://synergystudy.me/log/date/{$ca_link}\">$day</a></td>\n";
            
          }else{
            //平日の処理
            echo "<td class=\"usual calendar\"><a class=\"usual calendar\" href=\"http://synergystudy.me/log/date/{$ca_link}\">$day</a></td>\n";
          }
          //土曜日で改行処理をする
          if($wday ==6){
            echo"</tr><tr>";
          }
        
          $day++;
          $wday++;
          $wday = $wday % 7;
        }

        //カレンダーの最後の空白部分
        if($wday > 0){
          while($wday < 7){
            echo "<td class=\"usual\">　</td>\n";
            $wday++;
          }
        }else{
          echo "<td colspan =7></td>\n";
        }
        echo "</tr></table>";
      ?>
    </table>
  </div><!--div calendar-->
  <span id="cal-legend"><i class="icon-calendar" style="vertical-align: bottom"></i> 今日は<?php echo arrange_date(date('Y-m-d'))?>です</span>
  