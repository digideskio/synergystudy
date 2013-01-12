<!DOCTYPE html>
<html lang="ja">
<head>
  <script type='text/javascript'>
    google.load('visualization', '1', {packages:['table']});
    google.setOnLoadCallback(drawTable);
    function drawTable() {
      var data = new google.visualization.DataTable();
      data.addColumn('string', '名前');
      data.addColumn('string', '学習目標');
      data.addColumn('string', '期日');
      data.addColumn('string', '期日まで');
      //改行処理は応急処置なのでのちのち
      data.addRows([
                  <?php foreach ($member_goals as $data): ?>                        
                        ['<?php echo strstr($data->fb_name, ' ', true) ?>', "<?php echo str_replace(array("\r\n","\r","\n"),'',$data->goal) ?>",'<?php echo arrange_date($data->period) ?>','あと<?php echo $data->diff ?>日'],
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
     
      var table = new google.visualization.Table(document.getElementById('gr-goals'));
      table.draw(data, {showRowNumber: true,cssClassNames:cssClassName});
      
    }
  </script>
</head>
<body>

  <div id="gr-goals-wrap">
    <h3>グループみんなの学習計画を見てみよう(ΦωΦ)ジーッ…</h3>
    <div id='gr-goals'></div>
  </div>
</body>
</html>