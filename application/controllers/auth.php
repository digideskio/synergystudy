<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends CI_Controller {
  
       public function __construct()
       {
         parent::__construct();      
         $this->fbauth = $this->config->item('fbauth');
         $this->load->library('facebook',$this->fbauth);
         $this->load->helper('date');
       }

	public function index(){
        redirect('auth/login');
	}
    
    public function login(){
        //Comprobate if the user request a strategy
        if($this->uri->segment(3)==''){
            $ci_config = $this->config->item('opauth_config');
            $arr_strategies = array_keys($ci_config['Strategy']);
            
            echo("Please, select an Oauth provider:<br />");
            echo("<ul>");
            foreach($arr_strategies AS $strategy){
                echo("<li><a href='".base_url()."auth/login/".strtolower($strategy)."'>Login with ".$strategy."</li>");
            }
            echo("</ul>");
        }   
        else{
            //Run login
            $this->load->library('Opauth/Opauth', $this->config->item('opauth_config'), false);
            $this->opauth->run();    
        }     
    }
    
    function authenticate(){
        //Create authenticate logic
        $response = unserialize(base64_decode( $_POST['opauth'] ));
        
        //echo("<pre>");
        //print_r($response);
        //echo("</pre>");
        
        $fb_id = $response['auth']['uid'];
        $fb_name =  $response['auth']['info']['name'];
        $access_token = $response['auth']['credentials']['token'];
        $gender =  $response['auth']['raw']['gender'];
                
        $newdata = array(
                   'access_token'  => $access_token,
                   'fb_id'     => $fb_id,
                   'fb_name' => $fb_name
                   );

        $this->session->set_userdata($newdata);
        
        $userinfo = $this->facebook->getUser();
        $this->session->set_userdata('userinfo',$userinfo);
        
        redirect('/home','location');
        
        
      //ここで各種ユーザーデータをDBに保存する
      //アクセストークンの取得テスト
      /*
      $access_token = $this->facebook->getAccessToken();      
      $this->session->userdata('access_token',$access_token);
      $data['access_token'] = $access_token;

      $fb_id = $this->facebook->getUser();
      $this->session->set_userdata('fb_id', $fb_id);
      
      //ユーザー情報の取得
      $userinfo = $this->facebook->api('/me');
      $data['userinfo'] = $userinfo;
      
      //グループ情報の取得
      $data['groupinfo'] = $this->facebook->api('me/groups');      
            
      //最終学歴を取得する(※学歴は登録していない可能性もある)
      $sch_id = '';
      $sch_name = '';
      
      if(isset($userinfo['education'])){
        $edu = $userinfo['education'];
        $edu_reverse = array_reverse($edu);
        $sch_id = $edu_reverse['0']['school']['id'];
        $sch_name = $edu_reverse['0']['school']['name'];
        
        //ユーザーの最終学歴の学校が既に登録されているか否かを判別する
        if($this->User_model->is_school($sch_id) == 0){
          //学校情報のDB登録
          $this->User_model->school_register($sch_id,$sch_name);
        }
      }
      
      //ユーザー情報が既に登録されているか否かを判別する
      if($this->User_model->is_register($fb_id) == 0){        
        //ユーザー情報のDB登録
        $this->User_model->user_register($fb_id,$userinfo['name'],$userinfo['gender'],$sch_id,date('Y-m-d H:i:s'));
      }  
      redirect('/home','location');
      */
    }
    
    public function logout(){
        //Create logout logic.
    }
    
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */