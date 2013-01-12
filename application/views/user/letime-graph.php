<div id="letime-graph-wrap" style="width:550px; height:400px; float:left;">
  <script type="text/javascript">
    
    google.load('visualization', '1', {packages: ['corechart']});
    
    function drawVisualization(){
      
      var data = google.visualization.arrayToDataTable([
        ['日付', '学習時間(分)'],        
        ['<?php echo str_replace('-','/',substr($six_d_ago,6)) ?>', <?php if($log7){echo $log7->total_time;}else{echo 0;} ?>],
        ['<?php echo str_replace('-','/',substr($five_d_ago,6)) ?>', <?php if($log6){echo $log6->total_time;}else{echo '0';} ?>],
        ['<?php echo str_replace('-','/',substr($four_d_ago,6)) ?>', <?php if($log5){echo $log5->total_time;}else{echo '0';} ?>],
        ['<?php echo str_replace('-','/',substr($three_d_ago,6)) ?>', <?php if($log4){echo $log4->total_time;}else{echo '0';} ?>],
        ['<?php echo str_replace('-','/',substr($two_d_ago,6)) ?>', <?php if($log3){echo $log3->total_time;}else{echo '0';} ?>],
        ['<?php echo str_replace('-','/',substr($yesterday,6)) ?>', <?php if($log2){echo $log2->total_time;}else{echo '0';} ?>],
        ['<?php echo str_replace('-','/',substr($today,6)) ?>', <?php if($log){echo $log->total_time;}else{echo '0';} ?>],
      ]);

      // Create and draw the visualization.
      new google.visualization.ColumnChart(document.getElementById('letime-graph')).
        draw(data,
          {title:"最近1週間の学習時間の推移",
           width:550, 
           height:350,
           hAxis: {title: "日付"},
           vAxis: {title: "学習時間"}
          }
          );
    }
    
    google.setOnLoadCallback(drawVisualization);    

  </script>
  <div id="letime-graph"></div>
</div><!-- #letime-graph-wrap -->

<div class="graph-legend" id="letime-legend">
  <table class="table table-striped original-table">
    <tbody>
      <tr>
        <td><strong>全ての学習時間の合計</strong></td>
        <td><?php if($sum_lt->total_time){echo $sum_lt->total_time;}else{ echo '0';} ?>分</td>
      </tr>
      <tr>
        <td><strong>全ての学習時間の平均</strong></td>
        <td><?php if($avg_lt->total_time){echo round($avg_lt->total_time,1);}else{echo '0';}?>分／日</td>
      </tr>
      <tr>
        <td><strong>最近1週間の学習時間の合計</strong></td>
        <td><?php if($sum_week_lt->total_time){echo $sum_week_lt->total_time;}else{echo '0';} ?>分</td>
      </tr>
      <tr>
        <td><strong>最近1週間の学習時間の平均</strong></td>
        <td><?php if($avg_week_lt->total_time){echo round($avg_week_lt->total_time,1);}else{echo '0';} ?>分／日</td>
      </tr>
      <tr>
        <td><strong>最近3日間の学習時間の平均</strong></td>
        <td><?php if($sum_reclt->total_time){echo $sum_reclt->total_time;}else{echo '0';} ?>分／日</td>
      </tr>
      <tr>
        <td><strong>最近3日間の学習時間の平均</strong></td>
        <td><?php if($avg_reclt->total_time){echo round($avg_reclt->total_time,1);}else{echo '0';} ?>分／日</td>
      </tr>
    </tbody>
  </table>
</div><!-- #letime-legend -->
  