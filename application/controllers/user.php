<?php
  class User extends CI_Controller{
    
    //コンストラクタ
    public function __construct()
    {
      parent::__construct();
      $this->fbauth = $this->config->item('fbauth');
      $appId = $this->fbauth['appId'];
      $secret = $this->fbauth['secret'];
      $fb_params = array('appId' => $appId, 'secret' => $secret);
      $this->load->library('facebook',$fb_params);
    }

    /*------------------------
      個人の学習記録の閲覧
     ------------------------*/
    public function index(){
      
      //セッション持ってなかったら弾く      
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
      
      //セッションデータの取得
      $fb_id = $this->session->userdata('fb_id');
      $access_token = $this->session->userdata('access_token');      
      $data['fb_id'] = $fb_id;
      $data['fb_name'] = $this->session->userdata('fb_name');
      $data['picture'] = $this->session->userdata('picture');
                              
      //日付変数の呼び出し
      $today = $this->config->item('today');
      $yesterday = $this->config->item('yesterday');
      $two_d_ago = $this->config->item('twodays_ago');
      $three_d_ago = $this->config->item('threedays_ago');
      $four_d_ago = $this->config->item('fourdays_ago');
      $five_d_ago = $this->config->item('fivedays_ago');
      $six_d_ago = $this->config->item('sixdays_ago');
      $week_ago = $this->config->item('oneweek_ago');

      //ビューへ
      $data['today'] = $this->config->item('today');
      $data['yesterday'] = $this->config->item('yesterday');
      $data['two_d_ago'] = $this->config->item('twodays_ago');
      $data['three_d_ago'] = $this->config->item('threedays_ago');
      $data['four_d_ago'] = $this->config->item('fourdays_ago');
      $data['five_d_ago'] = $this->config->item('fivedays_ago');
      $data['six_d_ago'] = $this->config->item('sixdays_ago');
      
      //１週間分の学習記録を取得する
      $data['log'] = $this->User_model->get_learning($fb_id,$today);
      $data['log2'] = $this->User_model->get_learning($fb_id,$yesterday);
      $data['log3'] = $this->User_model->get_learning($fb_id,$two_d_ago);
      $data['log4'] = $this->User_model->get_learning($fb_id,$three_d_ago);
      $data['log5'] = $this->User_model->get_learning($fb_id,$four_d_ago);
      $data['log6'] = $this->User_model->get_learning($fb_id,$five_d_ago);
      $data['log7'] = $this->User_model->get_learning($fb_id,$six_d_ago);
                  
      //ユーザーが学習記録を共有しているグループの取得
      $data['groups'] = $this->Group_model->get_activegroups($fb_id);
       
      //全期間の総合学習時間の取得
      $data['sum_lt'] = $this->User_model->get_sum_lt($fb_id);

      //全期間の平均学習時間の取得
      $data['avg_lt'] = $this->User_model->get_avg_lt($fb_id);
      
      //直近１週間の総合学習時間
      $data['sum_week_lt'] = $this->User_model->get_sum_week_lt($fb_id);
      
      //直近１週間の平均学習時間      
      $data['avg_week_lt'] = $this->User_model->get_avg_week_lt($fb_id);

      //直近１週間の総合学習時間
      $data['sum_reclt'] = $this->User_model->get_sum_reclt($fb_id);
      
      //直近１週間の平均学習時間      
      $data['avg_reclt'] = $this->User_model->get_avg_reclt($fb_id);
      
      //平均自己評価（全期間）
      $data['allsr'] = $this->User_model->get_allsr($fb_id);
      
      //平均自己評価（1週間）
      $data['weeksr'] =$this->User_model->get_weeksr($fb_id);
      
      //平均自己評価（直近三日間）
      $data['recsr'] =$this->User_model->get_recsr($fb_id);
      
      //コメント
      $data['comments'] = $this->User_model->get_comments($fb_id);
      
      //コメントと学習内容
      $data['com_cont'] = $this->User_model->com_and_content($fb_id);

      //目標の取得
      $data['goals'] = $this->User_model->get_goals($fb_id);

      //最新の学習計画の取得
      //$data['plan'] =$this->User_model->get_plans($fb_id);
      //$data['plans'] =$this->User_model->get_allplans($fb_id);
      
      //ビューの読み込み
      $data['header'] = $this->load->view('header',$data,true);
      $data['footer'] = $this->load->view('footer',$data,true);
      $this->load->view('userview',$data);
      
      }catch(FacebookApiException $e) {
        redirect('/login/','location');
      }
    }

    
    //学習目標の新規登録
    public function submit_goal(){
      //送信ボタンが押されているかどうかのチェック（直アクセス防止）
      if(!$this->input->post('submit_goal')){
        redirect('/user/goal','location');
      }
      
      //バリデーション
      $fb_id = $this->session->userdata('fb_id');
      $goal = $this->input->post('goal');
      $period = $this->input->post('period');
      $setted_at = date('Y-m-d H:i:s');
      
      //学習目標登録モデルへ
      $this->User_model->set_goal($fb_id,$goal,$period,$setted_at);
      
      redirect('/user','location');      
    }
    
    
    //学習目標の編集
    public function edit_goal($fb_id){
      //送信ボタンが押されているかどうかのチェック（直アクセス防止）
      if(!$this->input->post('edit_goal')){
        redirect('/user','location');
      }
      
      //セッションに入っているFBIDと引数で受け取ったFBIDの整合性の検証
      $true_fb_id = $this->session->userdata('fb_id');
      if($true_fb_id != $fb_id){
        redirect('/user','location');
      }
            
      //バリデーション
      $fb_id = $this->session->userdata('fb_id');
      $goal = $this->input->post('goal');
      $period = $this->input->post('period');
      $goal_id = $this->input->post('goal_id');
      $setted_at = date('Y-m-d H:i:s');
      
      //学習目標更新モデルへ
      $this->User_model->update_goal($fb_id,$goal,$period,$goal_id,$setted_at);
      
      redirect('/user','location');
    }

    
    public function remove_goal($goal_id,$fb_id){
      //セッションに入っているFBIDと引数で受け取ったFBIDの整合性の検証
      $true_fb_id = $this->session->userdata('fb_id');
      if($true_fb_id != $fb_id){
        redirect('/user','location');
      }
      
      //学習目標削除モデルへ
      $this->User_model->drop_goal($goal_id);
      redirect('/user#goal','location');
    }
    
  } 
?>
