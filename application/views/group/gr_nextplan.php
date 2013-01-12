<!DOCTYPE html>
<html lang="ja">
<head>
  <script type='text/javascript'>
    google.load('visualization', '1', {packages:['table']});
    google.setOnLoadCallback(drawTable);
    function drawTable() {
      var data = new google.visualization.DataTable();
      data.addColumn('string', '名前');
      data.addColumn('string', '次回の予定');
      data.addColumn('string', '記録日');
      //改行処理は応急処置なのでのちのち
      data.addRows([
                  <?php foreach ($le_plan as $data): ?>                        
                        ['<?php echo strstr($data->fb_name, ' ', true) ?>', "<?php echo str_replace(array("\r\n","\r","\n"),'',$data->nextplan) ?>",'<?php echo arrange_date($data->register_at) ?>'],
                  <?php endforeach; ?>
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
     
      var table = new google.visualization.Table(document.getElementById('gr-nextplan'));
      table.draw(data, {showRowNumber: true,cssClassNames:cssClassName});
      
    }
  </script>
</head>
<body>

  <div id="gr-nextplan-wrap">
    <h3>グループのみんなが学習計画を守れているかチェックしてみよう(ΦωΦ)ﾌﾌﾌ…</h3>
    <div id='gr-nextplan'></div>
  </div>
</body>
</html>