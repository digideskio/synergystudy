<?php
  class Login extends CI_Controller {
        
    public function __construct(){
      parent::__construct();
      $this->fbauth = $this->config->item('fbauth');
      $appId = $this->fbauth['appId'];
      $secret = $this->fbauth['secret'];
      $fb_params = array('appId' => $appId, 'secret' => $secret);
      $this->load->library('facebook',$fb_params);
      //この処理が合っているかという問題点もある
      //$this->fbauth = $this->config->item('fbauth');
      //$this->load->library('facebook',$this->fbauth);      
    }
    
    
    public function index(){      
      $this->load->view('loginview2');
    }

    
    public function auth(){
      
      //configファイルからIDとSECRETを取り出す
      $appId = $this->config->item('appId', 'fbauth');
      $secret = $this->config->item('secret', 'fbauth');
      $site_url = 'http://synergystudy.me/login/auth/';
      $my_url = 'http://synergystudy.me/';
      //$redirect_url = 'http://synergystudy.me/login/auth/';
      //$site_url = 'http://synergystudy.me/home/';
try {      
      if (empty($_GET['code'])) {
        
        //認証前の処理・認証ダイアログを作成
      
        //CSRF対策
        $this->session->set_userdata('state',sha1(uniqid(mt_rand(), true)));
        $state = $this->session->userdata('state');

        $params = array(
          'client_id' => $appId,
          'redirect_uri' => $site_url,
          'state' => $state,
          'scope' => 'publish_stream,user_groups,user_education_history'
        );

        $url = 'https://www.facebook.com/dialog/oauth?'.http_build_query($params);
        header('Location: '.$url);

        exit;
      }else{
        
        // 認証されて帰ってきた時の処理
        $state = $this->session->userdata('state');
        if ($state != $_GET['state']) {
          echo "ERROR!!";
          exit;
        }
        
        // ユーザー情報の取得
        $params = array(
          'client_id' => $appId,
          'client_secret' => $secret,
          //'grant_type' => 'client_credentials',
          'code' => $_GET['code'],
          'redirect_uri' => $site_url
        );

        $url = 'https://graph.facebook.com/oauth/access_token?' . http_build_query($params);
        
        $body = file_get_contents($url);        
        parse_str($body); //変数「$access_token」ができている
        
        //$fb_params = array('appId' => $appId, 'secret' => $secret);
        //$this->load->library('facebook',$fb_params);
                
        $url = 'https://graph.facebook.com/me?access_token='.$access_token.'&fields=name,gender,picture,education';
        $me = json_decode(file_get_contents($url));
                
        //DB登録やsession保存に必要なデータを変数に格納する
        $fb_id = $me->id;
        $fb_name = $me->name;
        $picture = $me->picture;
        $gender = $me->gender;
        //$groupinfo = $this->facebook->api('/'.$fb_id.'/groups');
                
        if(isset($me->education)){
          $education = $me->education;
        }
        
        
        //セッションへの保存（動作確認済み）
        $userdata = array('fb_id' => $fb_id,
                          'fb_name' => $fb_name,
                          'picture' => $picture,
                          'access_token' => $access_token
                         );
        $this->session->set_userdata($userdata);
        
        
        //最終学歴を取得する(※学歴は登録していない可能性もある)
        $sch_id = '';
        $sch_name = '';        
        
        if(isset($education)){ 
        
          //ゴリ押しだけど、最終学歴を配列として取得する  
          $edu_array = array();
          foreach($education as $e){
            $edu_array['id'] = $e->school->id;
            $edu_array['name'] = $e->school->name;
            $edu_array['type'] = $e->type;
          }
          
          $sch_id = $edu_array['id'];
          $sch_name = $edu_array['name'];
          $sch_type = $edu_array['type'];
          
          if($this->User_model->is_school($sch_id) == 0){
            //学校情報のDB登録
            $this->User_model->school_register($sch_id,$sch_name,$sch_type);
          }
          
        }
        
        //ユーザー情報が既に登録されているか否かを判別する
        if($this->User_model->is_register($fb_id) == 0){        
          //ユーザー情報のDB登録
          $this->User_model->user_register($fb_id,$fb_name,$gender,$sch_id,$picture,$access_token,date('Y-m-d H:i:s'));
        }
        
        //redirect('/home/','location');
        redirect('/home','location');
        
      }
      
} catch(FacebookApiException $e) {
  /*
  $params = array(
          'client_id' => $appId,
          'redirect_uri' => $site_url,
          'state' => $state,
          'scope' => 'publish_stream,user_groups,user_education_history'
        );
  */
  //$login_url = $this->facebook->getLoginUrl($params);
  //redirect($login_url,'location');
  //redirect('/home/','location');
  redirect('/home','location');
}      
    }
    

}

?>