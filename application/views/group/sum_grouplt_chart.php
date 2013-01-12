  <script type="text/javascript">
    google.load("visualization", "1", {packages:["corechart"]});
    google.setOnLoadCallback(drawChart);
    function drawChart() {
      var data = google.visualization.arrayToDataTable([
        ['ユーザー名', '合計学習時間'],
        <?php foreach ($detail_grouplt as $data): ?>
        ['<?php echo strstr($data->fb_name, ' ', true) ?>',<?php echo $data->total ?>],
        <?php endforeach; ?>
      ]);
                  
      var options = {title:'グループ全体の学習時間の内訳',
                     chartArea:{left:45,top:45,width:"250%",height:"250%"},
                     legend:{position: 'right'},
                     titleTextStyle: {color: 'black',fontSize:13}
                    };
      
      
      var chart = new google.visualization.PieChart(document.getElementById('sum_grouplt_chart'));
      chart.draw(data,options);
    }
  </script>
  <div id="sum_grouplt_chart" style="height:350px; width:450px; float:left;"></div>
  <div id="sgc-legend" style="width:450px; float:left;">
    <?php echo "→グループ『{$sum_grouplt->group_name}』のメンバー全員の合計学習時間は{$sum_grouplt->total}分です" ?>
    <?php foreach($detail_grouplt as $data): ?>
    <?php echo "{$data->fb_name} ｜{$data->total}分<br />" ?>
    <?php endforeach;?>
    <?php //include('group/sum_grouplt_chart.php') ?>
  </div>