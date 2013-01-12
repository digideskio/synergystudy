<!DOCTYPE html>
<html lang="ja">
<head>
  <script type='text/javascript'>
    google.load('visualization', '1', {packages:['table']});
    google.setOnLoadCallback(drawTable);
    function drawTable() {
      var data = new google.visualization.DataTable();
      data.addColumn('string', '学習計画');
      data.addColumn('string', '記録日');
      data.addColumn('string', '達成度');
      
      data.addRows([
                  <?php if($plans):?>
                    <?php foreach ($plans as $data): ?>                        
                      ["<?php echo str_replace(array("\r\n","\r","\n"),'',$data->nextplan) ?>",                       
                       '<?php echo arrange_date($data->register_at) ?>',
                       '◯'
                      ],
                    <?php endforeach; ?>
                  <?php else:?>
                    ['まだ登録しているデータがありません','まだ登録しているデータがありません','まだ登録しているデータがありません'],
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
     
      var table = new google.visualization.Table(document.getElementById('plan-table'));
      table.draw(data, {showRowNumber: true,cssClassNames:cssClassName});
      
    }
  </script>
</head>
<body>

  <div id="plan-table-wrap">
    <h2>学習計画を振り返る<!--(ΦωΦ)ﾌﾌﾌ…--></h2>
    <div id='plan-table'></div>
  </div>
</body>
</html>