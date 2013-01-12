<?php
  class Log extends CI_Controller{
    
    //コンストラクタ
    public function __construct()
    {
      parent::__construct();      
      $this->fbauth = $this->config->item('fbauth');
      $appId = $this->fbauth['appId'];
      $secret = $this->fbauth['secret'];
      $fb_params = array('appId' => $appId, 'secret' => $secret);
      $this->load->library('facebook',$fb_params);
      $this->load->helper('form');
    }
    
    
    public function date($date = null){
            
      //セッションを持っていない場合は弾く
      if(!$this->session->userdata('fb_id')){
        redirect('/login', 'location');
      }
      
      //APIが機能していない場合に弾く
      $login = $this->facebook->getUser();
      if(!$login){
        redirect('/login','location');
      }
      
      //テスト中
      try{
            
      //引数の部分、正規表現で数字以外のものは弾くようにする
      
      //引数が空の場合は当日のデータ画面へ遷移する
      if($date == null){
        $today = date('Ymd');
        redirect("/log/date/{$today}","location");
      }
      
      //コントローラ内で利用する変数
      $fb_id = $this->session->userdata('fb_id');
      $year = substr($date,0,4);
      $month = substr($date,4,2);
      $day = substr($date,6,2);
      $register_at = "{$year}-{$month}-{$day}";

      //ビューに渡す変数の生成
      $data['fb_id'] = $fb_id;
      $data['fb_name'] = $this->session->userdata('fb_name');
      $data['picture'] = $this->session->userdata('picture');
      $data['date'] = $date;
      $data['date2'] = $register_at;
      $data['groupinfo'] = $this->facebook->api('/me/groups');
      $data['groups'] = $this->Group_model->get_activegroups($fb_id);

      //ビュー変数の生成
      $data['header'] = $this->load->view('header',$data,true);
      $data['footer'] = $this->load->view('footer',$data,true);
      $data['calendar'] = $this->load->view('calendar',$data,true);

      //既に登録されている日付のデータの場合、そのデータを取得・表示する。
      $count = $this->learning_model->is_log($fb_id,$register_at);
      if($count != 0){
        $data['log'] = $this->learning_model->get_log($fb_id,$register_at);
        $data['log2'] = $this->learning_model->get_log2($fb_id,$register_at);
        $gids = $this->learning_model->get_groupids($fb_id,$date);
        
        //配列に整形する
        foreach($gids as $gid){
          $groupids[] = $gid->group_id;        
        };
        $data['groupids'] = $groupids;

        $this->load->view('editview',$data);
        
      //登録されていない場合は新規登録ページへ
      }else{
        $this->load->view('logview',$data);
      }
      
      }catch(FacebookApiException $e) {
        redirect('/login/','location');
      }
      
    }

    
    
    //学習記録の登録
    public function submit_log($date){
      
      //直接アクセスを弾く・データが入っていない場合は弾くなどの処理
      if(!$this->input->post('log_button')){
        redirect('/home/','location');
      }
            
      //フォームの値を変数に格納する
      $fb_id = $this->session->userdata('fb_id');
      $fb_name = $this->session->userdata('fb_name');
      $fl_content = $this->input->post('fl_content');
      $sl_content = $this->input->post('sl_content');
      $tl_content = $this->input->post('tl_content');
      $fl_time = $this->input->post('fl_time');
      $sl_time = $this->input->post('sl_time');
      $tl_time = $this->input->post('tl_time');
      $total_time = $this->input->post('total_time');
      $comment = $this->input->post('comment');
      $selfreview = $this->input->post('selfreview');
      $group_id = $this->input->post('group');

      //日付変数の整形
      $year = substr($date,0,4);
      $month = substr($date,4,2);
      $day = substr($date,6,2);
      $register_at = "{$year}-{$month}-{$day}";
      
      //ビューにわたすデータの定義
      $data['fb_id'] = $fb_id;
      $data['fb_name'] = $this->session->userdata('fb_name');
      $data['picture'] = $this->session->userdata('picture');
      $data['date'] = $date;
      $data['date2'] = $register_at;
      $data['groupinfo'] = $this->facebook->api('/'.$fb_id.'/groups');
      $data['groups'] = $this->Group_model->get_activegroups($fb_id);
      $data['header'] = $this->load->view('header',$data,true);
      $data['footer'] = $this->load->view('footer',$data,true);
      $data['calendar'] = $this->load->view('calendar',$data,true);
                      
      //指定されたグループIDの数を取得する
      $count = count($group_id);
      
      //指定されたグループIDが１つだった場合の処理
      if($count == 1){
          
        //バリデーション
        $errors = $this->learning_model->validation($fl_content,$sl_content,$tl_content,$fl_time,$sl_time,$tl_time,$total_time,$comment,$selfreview,$group_id[0]);

        //選択されたグループIDが１つで、エラーがあった場合の処理（ビューへ返す）
        if($errors != null){
          $data['errors'] = $errors;
          $data['header'] = $this->load->view('header',$data,true);
          $data['footer'] = $this->load->view('footer',$data,true);
          $data['calendar'] = $this->load->view('calendar',$data,true);
          
          //editviewに返すか、logviewに返すかの条件分岐
          $count = $this->learning_model->is_log($fb_id,$register_at);
          if($count != 0){
            $this->load->view('editview',$data);
          }else{
            $this->load->view('logview',$data);
          }
            
        }else{
          //選択されたグループIDが１つで、エラーが無かった場合の処理
          //learningテーブルへ
          $this->learning_model->register_log($fb_id,$fl_content,$sl_content,$tl_content,$fl_time,$sl_time,$tl_time,$total_time,$group_id[0],$register_at);
                  
          //learning2テーブルへ
          $this->learning_model->register_log2($fb_id,$comment,$selfreview,$group_id[0],$register_at);
          
          //ウォールへ投稿
          $this->learning_model->wall_post($group_id[0],$fb_name,$fl_content,$sl_content,$tl_content,$total_time,$comment,$register_at);
          
          redirect("/log/date/{$date}?state=success","location");
        }

      }else{
        //指定されたグループが複数あった場合の処理        
        for($i=0;$count > $i;$i++){
            
          //バリデーション
          $errors = $this->learning_model->validation($fl_content,$sl_content,$tl_content,$fl_time,$sl_time,$tl_time,$total_time,$comment,$selfreview,$group_id[$i]);

          //選択されたグループIDが複数で、エラーがあった場合の処理（ビューへ返す）
          if($errors != null){
            $data['errors'] = $errors;
            $data['header'] = $this->load->view('header',$data,true);
            $data['footer'] = $this->load->view('footer',$data,true);
            $data['calendar'] = $this->load->view('calendar',$data,true);
            //editviewに返すか、logviewに返すかの条件分岐
            $count = $this->learning_model->is_log($fb_id,$register_at);
            if($count != 0){
              
              $this->load->view('editview',$data);
            }else{
              $this->load->view('logview',$data);
            }
          }else{
            //選択されたグループIDが複数で、エラーが無かった場合の処理  
            //learningテーブルへ
            $this->learning_model->register_log($fb_id,$fl_content,$sl_content,$tl_content,$fl_time,$sl_time,$tl_time,$total_time,$group_id[$i],$register_at);
          
            //learning2テーブルへ
            $this->learning_model->register_log2($fb_id,$comment,$selfreview,$group_id[$i],$register_at);
            
            //ウォールへ投稿
          $this->learning_model->wall_post($group_id[$i],$fb_name,$fl_content,$sl_content,$tl_content,$total_time,$comment,$date);
          }            
        }
        redirect("/log/date/{$date}?state=success","location");
      }
    }

    
    
    //学習記録の修正
    public function edit_log($date){
      
      //直接アクセスを弾く・データが入っていない場合は弾くなどの処理
      if(!$this->input->post('log_button')){
        redirect('/home/','location');
      }
      
      //フォームの値を変数に格納
      $fb_id = $this->session->userdata('fb_id');
      $fb_name = $this->session->userdata('fb_name');      
      $fl_content = $this->input->post('fl_content');
      $sl_content = $this->input->post('sl_content');
      $tl_content = $this->input->post('tl_content');
      $fl_time = $this->input->post('fl_time');
      $sl_time = $this->input->post('sl_time');
      $tl_time = $this->input->post('tl_time');
      $total_time = $this->input->post('total_time');
      $comment = $this->input->post('comment');
      $selfreview = $this->input->post('selfreview');
      $group_id = $this->input->post('group');
            
      //日付変数の整形
      $year = substr($date,0,4);
      $month = substr($date,4,2);
      $day = substr($date,6,2);
      $register_at = "{$year}-{$month}-{$day}";
      
      //ビューにわたすデータの定義
      $data['fb_id'] = $fb_id;
      $data['fb_name'] = $this->session->userdata('fb_name');
      $data['picture'] = $this->session->userdata('picture');
      $data['date'] = $date;
      $data['date2'] = $register_at;
      $data['groupinfo'] = $this->facebook->api('/'.$fb_id.'/groups');
      $data['groups'] = $this->Group_model->get_activegroups($fb_id);
      $data['header'] = $this->load->view('header',$data,true);
      $data['footer'] = $this->load->view('footer',$data,true);
      $data['calendar'] = $this->load->view('calendar',$data,true);
      
      //指定されたグループIDの数を取得する
      $count = count($group_id);      
      
      //指定されたグループIDが１つだった場合の処理
      if($count == 1){
        
        //バリデーション
        $errors = $this->learning_model->validation($fl_content,$sl_content,$tl_content,$fl_time,$sl_time,$tl_time,$total_time,$comment,$selfreview,$group_id[0]);

        //選択されたグループIDが１つで、エラーがあった場合の処理（ビューへ返す）
        if($errors != null){
          $data['errors'] = $errors;
          $data['header'] = $this->load->view('header',$data,true);
          $data['footer'] = $this->load->view('footer',$data,true);
          $data['calendar'] = $this->load->view('calendar',$data,true);
          
          //editviewに返すか、logviewに返すかの条件分岐
          $count = $this->learning_model->is_log($fb_id,$register_at);
          if($count != 0){
              $data['log'] = $this->learning_model->get_log($fb_id,$register_at);
              $data['log2'] = $this->learning_model->get_log2($fb_id,$register_at);
              $gids = $this->learning_model->get_groupids($fb_id,$date);
        
              //配列に整形する
              foreach($gids as $gid){
                $groupids[] = $gid->group_id;        
              };
              $data['groupids'] = $groupids;
            $this->load->view('editview',$data);
          }else{
            $this->load->view('logview',$data);
          }
            
        }else{
          //選択されたグループIDが１つで、エラーが無かった場合の処理
          
          //learningテーブルへ
          $this->learning_model->update_log($fb_id,$fl_content,$sl_content,$tl_content,$fl_time,$sl_time,$tl_time,$total_time,$group_id[0],$register_at);
                    
          //learning2テーブルへ
          $this->learning_model->update_log2($fb_id,$comment,$selfreview,$group_id[0],$register_at);
          
          redirect("/log/date/{$date}?state=success","location");
        }        

      }else{
        
        //指定されたグループが複数あった場合の処理        
                   
        for($i=0;$count > $i;$i++){
          
          //バリデーション
          $errors = $this->learning_model->validation($fl_content,$sl_content,$tl_content,$fl_time,$sl_time,$tl_time,$total_time,$comment,$selfreview,$group_id[$i]);

          //選択されたグループIDが複数で、エラーがあった場合の処理（ビューへ返す）
          if($errors != null){
            $data['errors'] = $errors;
            $data['header'] = $this->load->view('header',$data,true);
            $data['footer'] = $this->load->view('footer',$data,true);
            $data['calendar'] = $this->load->view('calendar',$data,true);
            //editviewに返すか、logviewに返すかの条件分岐
            $count = $this->learning_model->is_log($fb_id,$register_at);
            if($count != 0){
              $this->load->view('editview',$data);
            }else{
              $this->load->view('logview',$data);
            }
          }else{
            //選択されたグループIDが複数で、エラーが無かった場合の処理  
            
            if(!$this->learning_model->update_log($fb_id,$fl_content,$sl_content,$tl_content,$fl_time,$sl_time,$tl_time,$total_time,$group_id[$i],$register_at)){
            $this->learning_model->register_log($fb_id,$fl_content,$sl_content,$tl_content,$fl_time,$sl_time,$tl_time,$total_time,$group_id[$i],$register_at);                    
            }
          
            if(!$this->learning_model->update_log2($fb_id,$comment,$selfreview,$group_id[$i],$register_at)){
              $this->learning_model->register_log2($fb_id,$comment,$selfreview,$group_id[$i],$register_at);
            }
          }          
        } //for閉じる
        
        //ウォールへ投稿
        
        
        redirect("/log/date/{$date}?state=success","location");
      }
    }
    
    
    
  } 
?>