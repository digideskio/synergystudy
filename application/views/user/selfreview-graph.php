<div id="sr-graph-wrap" style="width:550px; height:400px; float:left;">
  <script type="text/javascript">
    
    google.load('visualization', '1', {packages: ['corechart']});
    
    function drawVisualization(){
      
      var data = google.visualization.arrayToDataTable([
        ['日付', '自己評価(ポイント)'],        
        ['<?php echo str_replace('-','/',substr($six_d_ago,6)) ?>', <?php if($log7){echo $log7->selfreview;}else{echo '0';} ?>],
        ['<?php echo str_replace('-','/',substr($five_d_ago,6)) ?>', <?php if($log6){echo $log6->selfreview;}else{echo '0';} ?>],
        ['<?php echo str_replace('-','/',substr($four_d_ago,6)) ?>', <?php if($log5){echo $log5->selfreview;}else{echo '0';} ?>],
        ['<?php echo str_replace('-','/',substr($three_d_ago,6)) ?>', <?php if($log4){echo $log4->selfreview;}else{echo '0';} ?>],
        ['<?php echo str_replace('-','/',substr($two_d_ago,6)) ?>', <?php if($log3){echo $log3->selfreview;}else{echo '0';} ?>],
        ['<?php echo str_replace('-','/',substr($yesterday,6)) ?>', <?php if($log2){echo $log2->selfreview;}else{echo '0';} ?>],
        ['<?php echo str_replace('-','/',substr($today,6)) ?>', <?php if($log){echo $log->selfreview;}else{echo '0';} ?>],
      ]);

      // Create and draw the visualization.
      new google.visualization.ColumnChart(document.getElementById('sr-graph')).
        draw(data,
          {title:"最近1週間の自己評価の推移",
           width:550, 
           height:350,
           hAxis: {title: "日付"},
           vAxis: {title: "自己評価"}
          }
          );
    }
    
    google.setOnLoadCallback(drawVisualization);    

  </script>
  <div id="sr-graph"></div>
</div><!-- #sr-graph-wrap -->

<div class="graph-legend">
  <table class="table table-striped original-table">
    <tbody>
      <tr>
        <td><strong>全ての自己評価の平均</strong></td>
        <td><?php echo round($allsr->selfreview,1) ?>ポイント</td>
      </tr>
      <tr>
        <td><strong>最近1週間の自己評価の平均</strong></td>
        <td><?php echo round($weeksr->selfreview,1) ?>ポイント</td>
      </tr>
      <tr>
        <td><strong>最近3日間の自己評価の平均</strong></td>
        <td><?php echo round($recsr->selfreview,1) ?>ポイント</td>
      </tr>
    </tbody>
  </table>
</div><!-- .graph-legend -->
