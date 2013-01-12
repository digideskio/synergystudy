<?php
  class Learning_model extends CI_Model{
    
    function __construct(){
      parent::__construct();
      $this->fbauth = $this->config->item('fbauth');
      $appId = $this->fbauth['appId'];
      $secret = $this->fbauth['secret'];
      $fb_params = array('appId' => $appId, 'secret' => $secret);
      $this->load->library('facebook',$fb_params);
      $this->load->database();
    }
    
    /*--------------------
      学習記録のDB登録1
     --------------------*/
    function register_log($fb_id,$fl_content,$sl_content,$tl_content,$fl_time,$sl_time,$tl_time,$total_time,$group_id,$register_at){

      //まだ存在しないgroupidの場合、groupテーブルへの登録
      if($this->Group_model->is_group($group_id) == 0){
        $this->Group_model->group_register($group_id);
      }

      //group_memberテーブルへの登録
      if($this->Group_model->is_member($fb_id,$group_id) == 0){
        $this->Group_model->member_register($fb_id,$group_id);
      }
      
      $data = array(
        'fb_id' => $fb_id,
        'fl_content' => $fl_content,
        'sl_content' => $sl_content,
        'tl_content' => $tl_content,
        'fl_time' => $fl_time,
        'sl_time' => $sl_time,
        'tl_time' => $tl_time,
        'total_time' => $total_time,
        'group_id' => $group_id,
        'register_at' => $register_at
      );
      
      $this->db->insert('learning',$data);
    }
    
    
    /*-------------------
      学習記録のDB登録2
     -------------------*/
    function register_log2($fb_id,$comment,$selfreview,$group_id,$register_at){
    
      $data = array(
        'fb_id' => $fb_id,
        'comment' => $comment,
        'selfreview' => $selfreview,        
        'group_id' => $group_id,
        'register_at' => $register_at
      );
      
      $this->db->insert('learning2',$data);
    }
    

    /*----------------------------------------------------------
      引数に指定された日付とIDの学習記録が存在するかのチェック
     ----------------------------------------------------------*/
    function is_log($fb_id,$register_at){
      $query = $this->db->get_where('learning',array('fb_id'=>$fb_id,'register_at'=>$register_at));
      return $query->num_rows();
    }
    
    
    /*------------------------------------------
      引数に指定された日付の学習記録を取得する
     ------------------------------------------*/
    function get_log($fb_id,$date){
      $query = $this->db->get_where('learning',array('fb_id'=>$fb_id,'register_at'=>$date));
      return $query->row();
    }
    
    
    /*---------------------------------------------------
      引数に指定された日付の学習記録を取得する（その２）
     ---------------------------------------------------*/
    function get_log2($fb_id,$date){
      $query = $this->db->get_where('learning2',array('fb_id'=>$fb_id,'register_at'=>$date));
      return $query->row();
    }
    
    
    /*----------------------------------------------------------------------
      投稿先グループIDを取得する（複数あった時のために、このメソッドが必要）
     ----------------------------------------------------------------------*/
    function get_groupids($fb_id,$date){
      $this->db->select('group_id');
      $this->db->from('learning2');
      $this->db->where('fb_id',$fb_id);
      $this->db->where('register_at',$date);
      $query = $this->db->get();
      return $query->result();
    }
    
    
    /*--------------------
      学習記録の修正登録
     --------------------*/
    function update_log($fb_id,$fl_content,$sl_content,$tl_content,$fl_time,$sl_time,$tl_time,$total_time,$group_id,$register_at){
      $data = array(
        'fb_id' => $fb_id,
        'fl_content' => $fl_content,
        'sl_content' => $sl_content,
        'tl_content' => $tl_content,
        'fl_time' => $fl_time,
        'sl_time' => $sl_time,
        'tl_time' => $tl_time,
        'total_time' => $total_time,
        'group_id' => $group_id,
        'register_at' => $register_at
      );
      
      $array = array('fb_id' => $fb_id, 'group_id' => $group_id, 'register_at' => $register_at);
      $this->db->where($array);
      $this->db->update('learning', $data);
    }
    

    /*-----------------------------
      学習記録の修正登録（その２）
     ------------------------------*/
    function update_log2($fb_id,$comment,$selfreview,$group_id,$register_at){
      $data = array(
        'fb_id' => $fb_id,
        'comment' => $comment,
        'selfreview' => $selfreview,
        'group_id' => $group_id,
        'register_at' => $register_at
      );
      
      $array = array('fb_id' => $fb_id, 'group_id' => $group_id, 'register_at' => $register_at);
      $this->db->where($array);
      $this->db->update('learning2', $data);
    }
    
    /*-----------------------------
      学習記録のバリデーション
     ------------------------------*/
    function validation($fl_content,$sl_content,$tl_content,$fl_time,$sl_time,$tl_time,$total_time,$comment,$selfreview,$group_id){

      //エラー配列の宣言
      $errors = array();
      
      //fl_contentの検証（入力必須の学習内容）
      if($fl_content == ''){
        $errors['fl_content_blank'] = "on";
      }elseif(mb_strlen($fl_content) > 60){
        $errors['fl_content_over'] = "on";
      }
      //日本語一文字３バイト計算（全角一文字？）→90までおｋ
      //mb_strlenの場合は全角一文字２バイト計算っぽい
      
      //sl_contentの検証
      if(mb_strlen($sl_content) > 60){
        $errors['sl_content_over'] = "on";
      }
      
      //tl_contentの検証
      if(mb_strlen($tl_content) > 60){
        $errors['tl_content_over'] = "on";
      }
      
      //fl_timeの検証（入力必須の学習時間）
      if($fl_time == ''){
        $errors['fl_time_blank'] = "on";
      }elseif($fl_time >= 1440){
        $errors['fl_time_over'] = "on";
      }elseif(strlen($fl_time) > 4){
        $errors['fl_time_str_over'] = "on";
      }elseif(!preg_match('/^[\d]+$/',$fl_time)){
        $errors['fl_time_hw'] = "on";
      }
      
      //sl_timeの検証
      if($sl_time){
        if($sl_time >= 1440){
          $errors['sl_time_over'] = "on";
        }elseif(mb_strlen($sl_time) > 4){
          $errors['sl_time_str_over'] = "on";        
        }elseif(!preg_match('/^[\d]+$/',$sl_time)){
          $errors['sl_time_hw'] = "on";
        }
      }

      //tl_timeの検証
      if($tl_time){
        if($tl_time >= 1440){
          $errors['tl_time_over'] = "on";
        }elseif(mb_strlen($tl_time) > 4){
          $errors['tl_time_str_over'] = "on";        
        }elseif(!preg_match('/^[\d]+$/',$tl_time)){
          $errors['tl_time_hw'] = "on";
        }  
      }
      
      //total_timeの検証
      if($total_time == ''){
        $errors['total_time_blank'] = "on";
      }elseif($total_time > 1440){
        $errors['total_time_over'] = "on";
      }elseif(mb_strlen($total_time) > 4){
        $errors['total_time_str_over'] = "on";        
      }elseif(!preg_match('/^[\d]+$/',$total_time)){
        $errors['total_time_hw'] = "on";
      }
      
      //selfreviewの検証
      if($selfreview == ''){
        $errors['selfreview_blank'] = "on";
      }elseif($selfreview > 5 || $selfreview < 1){
        $errors['selfreview_value'] = "on";
      }elseif(mb_strlen($selfreview) > 1){
        $errors['selfreview_str_over'] = "on";        
      }elseif(!preg_match('/^[\d]+$/',$selfreview)){
        $errors['selfreview_hw'] = "on";
      }
      
      //commentの検証
      if($comment == ''){
        $errors['comment_blank'] = "on";
      }elseif(mb_strlen($comment) > 1000){
        $errors['comment_over'] = "on";        
      }
      
      //group_idの検証
      $fb_id = $this->session->userdata('fb_id');
      
      //できるだけAPIの方は使わない方がいい。ここはデータベースに直すべき
      $groupinfo2 = $this->User_model->db_groups($fb_id);
      if($groupinfo2){
        foreach($groupinfo2 as $group){
          $groupids2[] = $group->group_id;
        }
      }      
      /*
      $groupinfo = $this->facebook->api('/'.$fb_id.'/groups');
      if($groupinfo){
        $groupids = array();
        foreach($groupinfo['data'] as $group){
          $groupids[] = $group['id'];
        }
      }
      */
      
      if($group_id == ''){
        $errors['group_id_blank'] = "on";
      }
      if(!in_array($group_id,$groupids2)){
        $errors['group_id_error'] = "on";
      }
      
      return $errors;
    }
    
    
    function wall_post($group_id,$fb_name,$fl_content,$sl_content,$tl_content,$total_time,$comment,$date){
      
      $text = "{$fb_name}さんが".arrange_date($date)."の学習を記録しました。\n
                   学習内容：{$fl_content}　{$sl_content}　{$tl_content}
                   総学習時間：{$total_time}分
                   コメント：{$comment}";
      $wall_post = $this->facebook->api("/{$group_id}/feed",'POST',array('message'=>$text));
      return $wall_post;
    }
    
    
  }
?>