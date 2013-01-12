<?php
  class Home extends CI_Controller{
    
    public function __construct()
    {
      parent::__construct();      
      $this->fbauth = $this->config->item('fbauth');
      $appId = $this->fbauth['appId'];
      $secret = $this->fbauth['secret'];
      $fb_params = array('appId' => $appId, 'secret' => $secret);
      $this->load->library('facebook',$fb_params);
    }
    
    
    public function index(){
      
      if(!$this->session->userdata('fb_id')){
          redirect('/login', 'location');
      }
      
      if(!$this->session->userdata('access_token')){
          redirect('/login', 'location');
      }
      
      
      //テスト中（謎のエラー対策）
      try{

      //セッションデータの取得
      $fb_id = $this->session->userdata('fb_id');
      $access_token = $this->session->userdata('access_token');      
      $data['fb_id'] = $fb_id;
      $data['fb_name'] = $this->session->userdata('fb_name');
      $data['picture'] = $this->session->userdata('picture');
      $data['access_token'] = $access_token;
      $data['date'] = date('Ymd');

      //グループ情報の取得
      $groupinfo = $this->facebook->api('/'.$fb_id.'/groups');
      $this->session->set_userdata('groupinfo',$groupinfo);
      //$data['groupinfo'] = $this->session->userdata('groupinfo');
      $data['groupinfo'] = $this->User_model->db_groups($fb_id);
      
            
      //グループ情報のDB登録
      foreach($groupinfo['data'] as $group){      
        //まだ存在しないgroupidの場合、groupテーブルへの登録
        if($this->Group_model->is_group($group['id']) == 0){
          $this->Group_model->group_register2($group['id'],$group['name']);
        }
        //group_memberテーブルへの登録
        if($this->Group_model->is_member($fb_id,$group['id']) == 0){
          $this->Group_model->member_register($fb_id,$group['id']);
        }        
      }
            
      $data['fb_id'] = $fb_id;
      
      //当日の日付情報を取得
      $today = date('Y-m-d');
      $data['today'] = $today;
      
      //当日の学習記録の有無をチェック
      $data['is_log'] = $this->User_model->is_log($fb_id,$today);
      
      //設定した目標を取得する
      $data['goals'] = $this->User_model->get_goals($fb_id);
      
      //ユーザーが学習記録を共有しているグループの取得
      $data['groups'] = $this->Group_model->get_activegroups($fb_id);
      
      //ランキング用変数
      $data['i'] = 1;

      //所属グループメンバーの目標を取得（期限が切れていない目標）
      $data['fri_goal'] = $this->User_model->friend_goal($fb_id);
      
      //所属するメンバー全員の最近の学習状況の取得
      $data['all_rec'] = $this->User_model->get_allrecdata($fb_id);

      //ビューデータの読み込み
      $data['header'] = $this->load->view('header',$data,true);      
      $data['footer'] = $this->load->view('footer',$data,true);     
      $this->load->view('homeview',$data);
      
      }catch(FacebookApiException $e) {
        redirect('/login/','location');
      }
    }
        
    
    //ホーム画面からの登録
    function submit_log2($date){
      
      //直接アクセスを弾く・データが入っていない場合は弾くなどの処理
      if(!$this->input->post('log_button')){
        redirect('/home/','location');
      }
            
      //フォームの値を変数に格納する
      $fb_id = $this->session->userdata('fb_id');
      $fb_name = $this->session->userdata('fb_name');
      $fl_content = $this->input->post('fl_content');
      $sl_content = '';//$this->input->post('sl_content');
      $tl_content = '';//$this->input->post('tl_content');
      $fl_time = $this->input->post('fl_time');
      $sl_time = 0; //$this->input->post('sl_time');
      $tl_time = 0; //$this->input->post('tl_time');
      $total_time = $this->input->post('fl_time'); //１の学習時間 ＝ 総合学習時間だから
      $comment = $this->input->post('comment');
      $selfreview = $this->input->post('selfreview');
      $group_id = $this->input->post('group');

      //日付変数の整形
      $year = substr($date,0,4);
      $month = substr($date,4,2);
      $day = substr($date,6,2);
      $register_at = "{$year}-{$month}-{$day}";
      
      //当日の学習が既に記録されている場合は弾く
      if($this->User_model->is_log($fb_id,$register_at) != 0){
        redirect('/home','location');
      }

      //バリデーション
      $errors = $this->learning_model->validation($fl_content,$sl_content,$tl_content,$fl_time,$sl_time,$tl_time,$total_time,$comment,$selfreview,$group_id);

      //エラーがあった場合の処理
      if($errors != null){
        $data['errors'] = $errors;
        //$data['header'] = $this->load->view('header',$data,true);
        //$data['footer'] = $this->load->view('footer',$data,true);
        //$data['calendar'] = $this->load->view('calendar',$data,true);
        
        //var_dump($errors);
        //$this->load->view('homeview',$data);
            
        }else{
          //エラーが無かった場合の処理
          //learningテーブルへ
          $this->learning_model->register_log($fb_id,$fl_content,$sl_content,$tl_content,$fl_time,$sl_time,$tl_time,$total_time,$group_id,$register_at);
                  
          //learning2テーブルへ
          $this->learning_model->register_log2($fb_id,$comment,$selfreview,$group_id,$register_at);
          
          //ウォールへ投稿
          $this->learning_model->wall_post($group_id,$fb_name,$fl_content,$sl_content,$tl_content,$total_time,$comment,$register_at);
          redirect("/home?state=success","location");
        }

      }
    
  } 
?>