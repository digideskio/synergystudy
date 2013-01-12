<?php
  class Group extends CI_Controller{

    public function __construct()
    {
      parent::__construct();      
      $this->fbauth = $this->config->item('fbauth');
      $appId = $this->fbauth['appId'];
      $secret = $this->fbauth['secret'];
      $fb_params = array('appId' => $appId, 'secret' => $secret);
      $this->load->library('facebook',$fb_params);
    }
    
    
    public function view($group_id = null){

      //セッション持ってなかったら弾く
      if(!$this->session->userdata('fb_id') || !$this->session->userdata('access_token')){
        redirect('/login', 'location');	
      }
      
      //APIが機能していない場合に弾く
      $login = $this->facebook->getUser();
      if(!$login){
        redirect('/login','location');
      }
      
      //テスト中
      try{
      
      /*--------------------------------------------
       group_idの桁数チェックと正規表現チェック
      ---------------------------------------------*/
      //15桁か否か
      if(mb_strlen($group_id) != 15){
        redirect('/home','location');        
      }
      
      //半角数字か否か
      if(!preg_match('/^[\d]+$/',$group_id)){
        redirect('/home','location');        
      }      
      
      /*---------------------------------------------
       引数にidが指定されていない場合はhomeへ弾く
      ---------------------------------------------*/
      if($group_id == ''){
        redirect('/home','location');
      }
      
      /*--------------------------------------------
       所属するグループ以外のIDが指定された場合は弾く
       ---------------------------------------------*/
      
      //コントローラ内で利用する変数の生成
      $fb_id = $this->session->userdata('fb_id');
      $access_token = $this->session->userdata('access_token');      

      //アクティブグループの取得
      $activegroupsId = $this->Group_model->get_activegroupsId($fb_id);          
      $gids = array();
      
      //データの整形
      foreach($activegroupsId as $hoge){
        $gids[] = $hoge->group_id;
      }
      
      if(in_array($group_id, $gids) !== TRUE){
        redirect('/home','location');
      }
      
      //ビューで利用する変数の生成
      $data['fb_id'] = $fb_id;
      $data['fb_name'] = $this->session->userdata('fb_name');
      $data['picture'] = $this->session->userdata('picture');            
      $data['group_id'] = $group_id;
      $data['access_token'] = $access_token;
      //$data['groupinfo'] = $this->session->userdata('groupinfo');
      $data['groupinfo'] = $this->facebook->api('/'.$group_id);
      $data['feed'] = $this->facebook->api('/'.$group_id.'/feed'); //ウォールのデータ
      $data['groups'] = $this->Group_model->get_activegroups($fb_id); //ユーザーが学習記録を共有しているグループの取得（必須）
      
      //直近3日間と直近1週間の日付情報
      $data['today'] = arrange_date(date('Y-m-d'));
      $data['th_daysago'] = arrange_date(date('Y-m-d',mktime(0,0,0,date('m'),date('d')-3,date('Y'))));
      $data['one_weekago'] = arrange_date(date('Y-m-d',mktime(0,0,0,date('m'),date('d')-7,date('Y'))));

      //グループ内でアクティブなメンバーを取得する（アクティブ＝記録を一度でもしている）
      $data['activemember'] = $this->Group_model->get_activemember($group_id);
            
      //グループメンバーの学習目標を取得する
      $data['member_goals'] = $this->Group_model->member_goals($group_id);
            
      //グループメンバーの学習時間の合計と平均をまとめて取得する（全期間）
      $data['all_lt'] = $this->Group_model->all_lt($group_id);

      //グループメンバーの学習時間の合計と平均をまとめて取得する（直近一週間）
      $data['week_lt'] = $this->Group_model->week_lt($group_id);
      
      //グループメンバーの学習時間の合計と平均をまとめて取得する（直近3日間）
      $data['recent_lt'] = $this->Group_model->recent_lt($group_id);

      //グループメンバーの自己評価の平均を期間ごとに取得する
      $data['allsr'] = $this->Group_model->allsr($group_id);
      $data['weeksr'] = $this->Group_model->weeksr($group_id);
      $data['recentsr'] = $this->Group_model->recentsr($group_id);
      
      //コメント情報の取得
      $data['comments'] = $this->Group_model->get_comment($group_id);
      
      //インクリメント用変数
      $data['i'] = 1;
      
      //学習記録ID情報の取得と配列化
      $logidinfo = $this->Group_model->get_logids($group_id);
      $log_ids = array();
      foreach($logidinfo as $info){
        $log_ids[] = $info['log_id'];
      }
      $data['log_ids'] = $log_ids;
      
      //ビューデータの読み込み
      $data['header'] = $this->load->view('header',$data,true);
      $data['footer'] = $this->load->view('footer',$data,true);      
      $this->load->view('groupview',$data);
      
      }catch(FacebookApiException $e) {
        redirect('/login/','location');
      }
      
    }

    
    //以下、使用しなくなったデータたち
      /*
      $subject = $this->input->post('subject');
      $contents = $this->input->post('contents');
      $date = date('Y-m-d H:i:s');      
      //ウォールへ投稿する
      $this->facebook->api("/{$group_id}/feed",'POST',array('message'=>$contents));
      */          
      //グループメンバーの最新の学習記録を取得する
      //$data['grouplearning'] = $this->Group_model->get_grouplearning($group_id);      
      //グループメンバー各々の最新の学習予定を取得する
      //$data['le_plan'] = $this->Group_model->le_plan($group_id);      
      //努力家トップの取得
      //$data['hard_worker'] = $this->Group_model->top_hard_worker($group_id);
      //安定感トップの取得
      //$data['stability'] = $this->Group_model->top_stability($group_id);
      //学習満足度トップの取得
      //$data['satisfaction'] = $this->Group_model->top_satisfaction($group_id);     
      //グループメンバー全員の学習時間の合計（全期間）
      //$data['sum_grouplt'] = $this->Group_model->sum_grouplt($group_id);
      //グループメンバー全員の学習時間の合計の内訳（全期間）
      //$data['detail_grouplt'] = $this->Group_model->detail_grouplt($group_id);
      //グループメンバーの学習時間の総計を期間毎に取得する
      //$data['sum_alltime'] = $this->Group_model->sum_alltime($group_id);
      //$data['sum_weektime'] = $this->Group_model->sum_weektime($group_id);
      //$data['sum_recenttime'] = $this->Group_model->sum_recenttime($group_id);      
      //グループメンバーの学習時間の平均を期間毎に取得する
      //$data['avg_alltime'] = $this->Group_model->avg_alltime($group_id);
      //$data['avg_weektime'] = $this->Group_model->avg_weektime($group_id);
      //$data['avg_recenttime'] = $this->Group_model->avg_recenttime($group_id);      

  } 
?>