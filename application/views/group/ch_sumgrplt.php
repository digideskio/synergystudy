<div id="sumgrplt-graph" style="width:580px; height:400px; float:left;">
  <script type="text/javascript">
  
    //グラフの形式をここで決めている？
    google.load("visualization", "1.1", {packages:["corechart"]});
  
    google.setOnLoadCallback(drawSumchart);

    function drawSumchart() {
  
      //記録開始からの全期間分のデータ
      var dataAll = [
        ['ユーザー名','総学習時間(分)'],
        <?php foreach($all_lt as $data): ?>
        ['<?php echo strstr($data->fb_name,' ',true);?>',<?php echo $data->s_time ?>],
        <?php endforeach;?>
      ];
  
      //直近一週間のデータ
      var dataWeek = [
        ['ユーザー名','総学習時間(分)'],
        <?php foreach($week_lt as $data): ?>
        ['<?php echo strstr($data->fb_name,' ',true);?>',<?php echo $data->s_time ?>],
        <?php endforeach;?>
      ];

      //直近３日間のデータ
      var dataRec = [
        ['ユーザー名','総学習時間(分)'],
        <?php foreach($recent_lt as $data): ?>
        ['<?php echo strstr($data->fb_name,' ',true);?>',<?php echo $data->s_time ?>],
        <?php endforeach;?>
      ];

      //変数dataに表示するグラフのデータを格納する
      var data = [];
      data[0] = google.visualization.arrayToDataTable(dataAll);
      data[1] = google.visualization.arrayToDataTable(dataWeek);
      data[2] = google.visualization.arrayToDataTable(dataRec);
  
      //オプション変数の生成（表示するグラフに関する設定をする）
      var options = {    
        width: 550,
        height: 380,
        vAxis: {title: "総学習時間(分)"},
        hAxis: {title: "名前"},
        seriesType: "bars",
        colors:['blue'],
        series: {5: {type: "line"}},
        animation:{
          duration: 1000,
          easing: 'out'
        }
      };
  
      //current変数（この変数を利用することで表示するグラフの切り替えを行う）
      var bval = 0;
  
      // Create and draw the visualization.  
      //BarChart→横 ComboChart→縦
      var chart = new google.visualization.ComboChart(document.getElementById('sumgrplt'));
      var button = document.getElementById('b1_sglt');
      var button2 = document.getElementById('b2_sglt');
      var button3 = document.getElementById('b3_sglt');

  
      function drawChart(){
        //Disabling the button while the chart is drawing.
        button.disabled = true;
        google.visualization.events.addListener(chart,'ready',
          function() {
            button.disabled = false;
          }
        );
      
        //グラフの名前の設定
        switch(bval){
          case 0:
            options['title'] = '全期間';
            break;
          case 1:
            options['title'] = "直近1週間（<?php echo $one_weekago ?> 〜 <?php echo $today ?>）";
            break;
          case 2:
            options['title'] = '直近3日間（<?php echo $th_daysago ?> 〜 <?php echo $today ?>）';
            break;
        }
    
        //表示するデータの値とグラフの設定データを取得し、グラフを出力する
        if(bval == 0||bval == 1||bval == 2){
          chart.draw(data[bval],options);
        }
    
      } //63行目閉じる
  
      //必須
      drawChart();
 
      button.onclick = function() {
        bval = 0;
        drawChart();
        all_rank();
      }
  
      button2.onclick = function(){
        bval = 1;
        drawChart();
        week_rank();
      }
  
      button3.onclick = function(){
        bval = 2;
        drawChart();
        recent_rank();
      }
    
      }//16行目閉じる
  
  </script>
  <button id="b1_sglt" class="btn" value="0">全期間</button>
  <button id="b2_sglt" class="btn" value="1">直近1週間</button>
  <button id="b3_sglt" class="btn" value="2">直近3日間</button>
  <div id="sumgrplt"></div>
</div>

<div id="sumgrplt-legend">
  <h3>総学習時間ランキング</h3>
  <ul class="unstyled">
    <?php foreach ($all_lt as $data): ?>
      <li>
        <div class="rank clearfix"><!--あまり300-->
          <div class="rank-num"><!--70-->
            <?php if ($i == 1): ?>
              <img src="/html/imgs/gold.png" heihgt="32px"/>
            <?php elseif ($i == 2): ?>
              <img src="/html/imgs/silver.png" heihgt="32px"/>
            <?php elseif ($i == 3): ?>
              <img src="/html/imgs/bronze2.png" heihgt="32px"/>
            <?php else: ?>
              <img src="/html/imgs/prize.png" heihgt="32px"/>
            <?php endif; ?>
            <span><?php echo $i ?>位</span>
          </div>
          <div class="rank-photo"><!--60-->
            <img src="https://graph.facebook.com/<?php echo $data->fb_id ?>/picture" height="50px" width="50px"/>          
          </div>
          <div class="rank-info"><!--158-->
            <div class="rank-user-name">
              <p><strong><?php echo strstr($data->fb_name, ' ', true) ?></strong></p>
            </div>
            <div class="rank-data">
              <p><span><?php echo $data->s_time ?>分</span></p>
            </div>
          </div>
        </div>      
      </li>
      <?php $i++ ?>
    <?php endforeach; ?>
  </ul>
</div>

<script>
    function all_rank(){
      var html = "<div id=\"sumgrplt-legend\">\n\
                    <h3>総学習時間ランキング</h3>\n\
                    <ul class=\"unstyled\">\n\
                    <?php $i = 1 ?>\n\
                    <?php foreach ($all_lt as $data): ?>\n\
                      <li>\n\
                        <div class=\"rank clearfix\"><!--あまり300-->\n\
                          <div class=\"rank-num\"><!--70-->\n\
                          <?php if ($i == 1): ?>\n\
                            <img src=\"/html/imgs/gold.png\" heihgt=\"32px\"/>\n\
                          <?php elseif ($i == 2): ?>\n\
                            <img src=\"/html/imgs/silver.png\" heihgt=\"32px\"/>\n\
                          <?php elseif ($i == 3): ?>\n\
                            <img src=\"/html/imgs/bronze2.png\" heihgt=\"32px\"/>\n\
                          <?php else: ?>\n\
                            <img src=\"/html/imgs/prize.png\" heihgt=\"32px\"/>\n\
                          <?php endif; ?>\n\
                            <span><?php echo $i ?>位</span>\n\
                          </div>\n\
                          <div class=\"rank-photo\"><!--60-->\n\
                            <img src=\"https://graph.facebook.com/<?php echo $data->fb_id ?>/picture\" height=\"50px\" width=\"50px\"/>\n\
                          </div>\n\
                          <div class=\"rank-info\"><!--158-->\n\
                            <div class=\"rank-user-name\">\n\
                              <p><strong><?php echo strstr($data->fb_name, ' ', true); ?></strong></p>\n\
                            </div>\n\
                            <div class=\"rank-data\">\n\
                              <p><span><?php echo $data->s_time ?>分</span></p>\n\
                            </div>\n\
                          </div>\n\
                        </div>\n\
                      </li>\n\
                    <?php $i++ ?>\n\
                    <?php endforeach; ?>\n\
                  </ul>\n\
                </div>";
      $('#sumgrplt-legend').replaceWith(html);
    }
    
    
    function week_rank(){
      var html = "<div id=\"sumgrplt-legend\">\n\
                    <h3>総学習時間ランキング</h3>\n\
                    <ul class=\"unstyled\">\n\
                    <?php $i = 1 ?>\n\
                    <?php foreach ($week_lt as $data): ?>\n\
                      <li>\n\
                        <div class=\"rank clearfix\"><!--あまり300-->\n\
                          <div class=\"rank-num\"><!--70-->\n\
                          <?php if ($i == 1): ?>\n\
                            <img src=\"/html/imgs/gold.png\" heihgt=\"32px\"/>\n\
                          <?php elseif ($i == 2): ?>\n\
                            <img src=\"/html/imgs/silver.png\" heihgt=\"32px\"/>\n\
                          <?php elseif ($i == 3): ?>\n\
                            <img src=\"/html/imgs/bronze2.png\" heihgt=\"32px\"/>\n\
                          <?php else: ?>\n\
                            <img src=\"/html/imgs/prize.png\" heihgt=\"32px\"/>\n\
                          <?php endif; ?>\n\
                            <span><?php echo $i ?>位</span>\n\
                          </div>\n\
                          <div class=\"rank-photo\"><!--60-->\n\
                            <img src=\"https://graph.facebook.com/<?php echo $data->fb_id ?>/picture\" height=\"50px\" width=\"50px\"/>\n\
                          </div>\n\
                          <div class=\"rank-info\"><!--158-->\n\
                            <div class=\"rank-user-name\">\n\
                              <p><strong><?php echo strstr($data->fb_name, ' ', true); ?></strong></p>\n\
                            </div>\n\
                            <div class=\"rank-data\">\n\
                              <p><span><?php echo $data->s_time ?>分</span></p>\n\
                            </div>\n\
                          </div>\n\
                        </div>\n\
                      </li>\n\
                    <?php $i++ ?>\n\
                    <?php endforeach; ?>\n\
                  </ul>\n\
                </div>";
      $('#sumgrplt-legend').replaceWith(html);
    }
    
    
    function recent_rank(){
      var html = "<div id=\"sumgrplt-legend\">\n\
                    <h3>総学習時間ランキング</h3>\n\
                    <ul class=\"unstyled\">\n\
                    <?php $i = 1 ?>\n\
                    <?php foreach ($recent_lt as $data): ?>\n\
                      <li>\n\
                        <div class=\"rank clearfix\"><!--あまり300-->\n\
                          <div class=\"rank-num\"><!--70-->\n\
                          <?php if ($i == 1): ?>\n\
                            <img src=\"/html/imgs/gold.png\" heihgt=\"32px\"/>\n\
                          <?php elseif ($i == 2): ?>\n\
                            <img src=\"/html/imgs/silver.png\" heihgt=\"32px\"/>\n\
                          <?php elseif ($i == 3): ?>\n\
                            <img src=\"/html/imgs/bronze2.png\" heihgt=\"32px\"/>\n\
                          <?php else: ?>\n\
                            <img src=\"/html/imgs/prize.png\" heihgt=\"32px\"/>\n\
                          <?php endif; ?>\n\
                            <span><?php echo $i ?>位</span>\n\
                          </div>\n\
                          <div class=\"rank-photo\"><!--60-->\n\
                            <img src=\"https://graph.facebook.com/<?php echo $data->fb_id ?>/picture\" height=\"50px\" width=\"50px\"/>\n\
                          </div>\n\
                          <div class=\"rank-info\"><!--158-->\n\
                            <div class=\"rank-user-name\">\n\
                              <p><strong><?php echo strstr($data->fb_name, ' ', true); ?></strong></p>\n\
                            </div>\n\
                            <div class=\"rank-data\">\n\
                              <p><span><?php echo $data->s_time ?>分</span></p>\n\
                            </div>\n\
                          </div>\n\
                        </div>\n\
                      </li>\n\
                    <?php $i++ ?>\n\
                    <?php endforeach; ?>\n\
                  </ul>\n\
                </div>";
      $('#sumgrplt-legend').replaceWith(html);
    }
</script>