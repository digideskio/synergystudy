<!DOCTYPE html>
<html lang="ja">
<head>
  <script type='text/javascript'>
    google.load('visualization', '1', {packages:['table']});
    google.setOnLoadCallback(drawTable);
    function drawTable() {
      var data = new google.visualization.DataTable();
      data.addColumn('string', '日付');
      data.addColumn('string', '学習内容');
      data.addColumn('string', 'コメント');
      
      
                  
      
      data.addRows([
                  <?php if($com_cont):?>
                    <?php foreach ($com_cont as $data): ?>                        
                      ['<?php echo arrange_date($data->register_at) ?>', 
                       '<?php 
                          echo $data->fl_content;
                          if ($data->sl_content){
                            echo "、{$data->sl_content}";
                          }
                          if ($data->tl_content){
                            echo "、{$data->tl_content}";
                          }
                        ?>',
                       '<?php echo $data->comment ?>'
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
     
      var table = new google.visualization.Table(document.getElementById('com-cont'));
      table.draw(data, {showRowNumber: true,cssClassNames:cssClassName});
      
    }
  </script>
</head>
<body>

  <div id="com-cont-wrap">
    <h2>学習内容とコメントを振り返る<!--(ΦωΦ)ﾌﾌﾌ…--></h2>
    <div id='com-cont'></div>
  </div>
</body>
</html>