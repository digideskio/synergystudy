<!DOCTYPE html>
<html lang="ja">
<head>
  <script type='text/javascript'>
    google.load('visualization', '1', {packages:['table']});
    google.setOnLoadCallback(drawTable);
    function drawTable() {
      var data = new google.visualization.DataTable();
      data.addColumn('string', '学習目標');
      data.addColumn('string', '期日');
      data.addColumn('string', '期日までの日数');      
      
      data.addRows([
                  <?php if($goals):?>
                    <?php foreach ($goals as $data): ?>                        
                      ['<?php echo $data->goal ?>',                       
                       '<?php echo arrange_date($data->period) ?>',
                       <?php if($data->diff > 0): ?>
                       'あと<?php echo $data->diff ?>日',
                       <?php else: ?>
                       '終了',
                       <?php endif; ?>
                      ],
                    <?php endforeach; ?>
                  <?php else:?>
                    ['まだ登録しているデータがありません','まだ登録しているデータがありません','まだ登録しているデータがありません','まだ登録しているデータがありません'],
                  <?php endif;?>
                   ]);        
        
      //クラス名の付与
      var cssClassName = {headerRow: 'headerRow',
                          tableRow: 'tableRow',
                          oddTableRow:'oddTableRow',
                          hoverTableRow: 'hoverTableRow',
                          selectedTableRow: 'selectedTableRow',
                          headerCell:'headerCell',
                          tableCell: 'tableCell'
                         };
     
      var table = new google.visualization.Table(document.getElementById('goal-table'));
      table.draw(data, {showRowNumber: true,cssClassNames:cssClassName});
      
    }
  </script>
</head>
<body>

  <div id="goal-table-wrap">
    <h3>学習目標の確認をする<!--(ΦωΦ)ﾌﾌﾌ…--></h3>
    <div id='goal-table'></div>
  </div>
</body>
</html>