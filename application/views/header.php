
  <div class="navbar  navbar-fixed-top">
    <div class="navbar-inner">
      <div class="container">
        <!--アプリ名-->
        <a class="brand" href="/home">SynergyStudy.me</a>
        
        <ul class="nav pull-right">          
          <li class="dropdown" id="glist" style="height:43px;"><a href="glist" class="dropdown-toggle" data-toggle="dropdown" data-target="#">グループの記録を見る <b class="caret"></b></a>
            <ul class="dropdown-menu">
              <?php if($groups): ?>
                <?php foreach($groups as $data):?>
                  <li>
                    <a href="/group/view/<?php echo $data->group_id ?>">
                      <?php echo $data->group_name ?>
                    </a>
                  </li>
                <?php endforeach;?>
              <?php else:?>
                  <li><a><span class="websymbols" style="color:red;">W</span> 閲覧できるグループがありません</a></li>
              <?php endif;?>
            </ul>
          </li>
          <li style="height:43px;"><a href="/user/">自分の記録を見る</a></li>
          <li style="height:43px;"><a href="/log/date/<?php echo date('Ymd')?>">学習の記録をする</a></li>

          <!--プルダウンメニュー-->
          <li class="dropdown" id="menu1">
            <a href="#menu1" class="dropdown-toggle" data-toggle="dropdown" data-target="#">
              <img src="<?php echo $picture ?>" height="23px" width="23px"/> <b class="caret"></b>
            </a>
            <ul class="dropdown-menu">
              <li><a><?php echo $fb_name ?></a></li>
              <li class="divider"></li>
              <li><a onclick="logout()">ログアウト</a></li>
            </ul>
          </li>
        </ul><!--プルダウンメニュー閉じる-->
      </div><!--class="container"-->
    </div><!--class="navbar-inner"-->
  </div><!--class="navbar"-->