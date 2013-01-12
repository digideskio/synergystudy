<?php
  class User_model extends CI_Model{
        
    function __construct(){
      parent::__construct();
      $this->load->database();
      //$this->today = $this->cofing->item('today');
      //$this->cofing->load('oneweek_ago');
    } 
    
    /*------------------------------------------------ 
      ユーザー名の取得
     ------------------------------------------------*/
    function get_name($fb_id){
      $this->db->select('fb_name');
      $this->db->from('user');
      $this->db->where('fb_id',$fb_id);
      $query = $this->db->get();
      return $query->row();
    }

    
    /*------------------------------------------------ 
      ユーザーがアプリに登録しているかどうかの検証
     ------------------------------------------------*/
    function is_register($fb_id){
      $query = $this->db->get_where('user', array('fb_id' => $fb_id));
      return $query->num_rows();
    }
    
    
    /*--------------------
      ユーザー情報の登録
     --------------------*/
    function user_register($fb_id,$fb_name,$gender,$sch_id,$picture,$access_token,$date){
      $data = array(
          'fb_id' => $fb_id,
          'fb_name' => $fb_name,
          'gender' => $gender,
          'school_id' => $sch_id,
          'picture' => $picture,
          'access_token' => $access_token,
          'started_at' => $date,
      );
      
      $this->db->insert('user',$data);      
    }

    
    /*-----------------------------------------------
      最終学歴の学校がDBに存在するかどうかを確かめる
     ------------------------------------------------*/
    function is_school($school_id){
      $query = $this->db->get_where('school', array('school_id' => $school_id));
      return $query->num_rows();
    }
    
    
    /*-------------------- 
      学校情報のDB登録
     --------------------*/
    function school_register($school_id,$school_name,$school_type){
      $data = array(
          'school_id' => $school_id,
          'school_name' => $school_name,
          'type' => $school_type
      );
      
      $this->db->insert('school',$data);
    }
    
    
    /*-------------------- 
      学習目標のDB登録
     --------------------*/
    function set_goal($fb_id,$goal,$period,$setted_at){
      
      //目標の数のカウント
      $goal_num = $this->User_model->count_goal($fb_id);
      $number = $goal_num + 1;
      
      $data = array(
          'fb_id'=>$fb_id,
          'goal'=>$goal,
          'period'=>$period,
          'setted_at'=>$setted_at,
          'number'=>$number
      );
      
      $this->db->insert('goal',$data);
    }
    

    /*-------------------- 
      学習目標の更新
     --------------------*/    
    function update_goal($fb_id,$goal,$period,$goal_id,$setted_at){
      $data = array(
          'fb_id'=>$fb_id,
          'goal'=>$goal,
          'period'=>$period,
          'goal_id'=>$goal_id,
          'setted_at'=>$setted_at
      );
      
      $this->db->where('goal_id',$goal_id);      
      $this->db->update('goal', $data); 
    }
    
    
    /*-------------------- 
      学習目標の削除
     --------------------*/  
    function drop_goal($goal_id){
      $this->db->where('goal_id',$goal_id);
      $this->db->delete('goal');
    }
    
        
    /*--------------------------------------------
      ユーザーの設定した目標の数をカウントする
     --------------------------------------------*/
    function count_goal($fb_id){
      $query = $this->db->get_where('goal',array('fb_id'=>$fb_id));
      return $query->num_rows();
    }

    
    /*--------------------------------------------
      ユーザーの設定した目標と期日までの日数を取得する
      ※ 期日をすぎているもの日数も取得する
     --------------------------------------------*/    
    function get_goals($fb_id){
      $date = date('Y-m-d');
      $sql = "select goal,period,goal_id,DATEDIFF(period,'{$date}') as diff
              from goal
              where fb_id = '{$fb_id}'
              order by period desc;";
      $query = $this->db->query($sql);
      return $query->result();
    }
    

    /*------------------------------
      指定された日付の学習記録を取得する
     ------------------------------*/
    function get_learning($fb_id,$date){
      $this->db->select('learning.total_time,learning2.selfreview');
      $this->db->from('learning');
      $this->db->join('learning2','learning.fb_id = learning2.fb_id','inner');
      $this->db->where('learning.fb_id',$fb_id);
      $this->db->where('learning.register_at',$date);
      $query = $this->db->get();
      return $query->row();
    }
    
    
    /*--------------------------------------------- 
      総合学習時間の取得（全期間）
     ---------------------------------------------*/
    function get_sum_lt($fb_id){
      $this->db->select_sum('total_time');
      $this->db->from('learning');
      $this->db->where('fb_id',$fb_id);
      $query = $this->db->get();
      return $query->row();
    }
    

    /*--------------------------------------------- 
      平均学習時間の取得（全期間）
     ---------------------------------------------*/
    function get_avg_lt($fb_id){
      $this->db->select_avg('total_time');
      $this->db->from('learning');
      $this->db->where('fb_id',$fb_id);
      $query = $this->db->get();
      return $query->row();
    }

    
    /*----------------------------------------------
      総合学習時間の取得（直近１週間）
     ----------------------------------------------*/
    function get_sum_week_lt($fb_id){
      $today = $this->config->item('today');
      $week_ago = $this->config->item('oneweek_ago');
      
      $this->db->select_sum('total_time');
      $this->db->from('learning');
      $this->db->where('fb_id',$fb_id);
      $this->db->where('register_at <=',$today);
      $this->db->where('register_at >=',$week_ago);
      $query = $this->db->get();
      return $query->row();
    }
    

    /*----------------------------------------------
      平均学習時間の取得（直近１週間）
     ----------------------------------------------*/
    function get_avg_week_lt($fb_id){
      $today = $this->config->item('today');
      $week_ago = $this->config->item('oneweek_ago');
      
      $this->db->select_avg('total_time');
      $this->db->from('learning');
      $this->db->where('fb_id',$fb_id);
      $this->db->where('register_at <=',$today);
      $this->db->where('register_at >=',$week_ago);
      $query = $this->db->get();
      return $query->row();
    }

    
    /*----------------------------------------------
      総合学習時間の取得（直近３日間）
     ----------------------------------------------*/
    function get_sum_reclt($fb_id){
      $today = $this->config->item('today');
      $three_d_ago = $this->config->item('threedays_ago');

      $this->db->select_sum('total_time');
      $this->db->from('learning');
      $this->db->where('fb_id',$fb_id);
      $this->db->where('register_at <=',$today);
      $this->db->where('register_at >=',$three_d_ago); 
      $query = $this->db->get();
      return $query->row();
    }
    
    /*----------------------------------------------
      平均学習時間の取得（直近３日間）
     ----------------------------------------------*/
    function get_avg_reclt($fb_id){
      $today = $this->config->item('today');
      $three_d_ago = $this->config->item('threedays_ago');

      $this->db->select_avg('total_time');
      $this->db->from('learning');
      $this->db->where('fb_id',$fb_id);
      $this->db->where('register_at <=',$today);
      $this->db->where('register_at >=',$three_d_ago);
      $query = $this->db->get();
      return $query->row();
    }

    
    /*------------------------------------- 
      自己評価の平均を取得する（全期間）
     -------------------------------------*/
    function get_allsr($fb_id){
      $this->db->select_avg('selfreview');
      $this->db->from('learning2');
      $this->db->where('fb_id',$fb_id);
      $query = $this->db->get();
      return $query->row();
    }

    
    /*-------------------------------------- 
      自己評価の平均を取得する（直近１週間）
     --------------------------------------*/
    function get_weeksr($fb_id){
      $today = $this->config->item('today');
      $week_ago = $this->config->item('oneweek_ago');
      
      $this->db->select_avg('selfreview');
      $this->db->from('learning2');
      $this->db->where('fb_id',$fb_id);
      $this->db->where('register_at <=',$today);
      $this->db->where('register_at >=',$week_ago);
      $query = $this->db->get();
      return $query->row();
    }

    
    /*--------------------------------------
      自己評価の平均を取得する（直近３日間）
     --------------------------------------*/
    function get_recsr($fb_id){
      $today = $this->config->item('today');
      $three_d_ago = $this->config->item('threedays_ago');
      
      $this->db->select_avg('selfreview');
      $this->db->from('learning2');
      $this->db->where('fb_id',$fb_id);
      $this->db->where('register_at <=',$today);
      $this->db->where('register_at >=',$three_d_ago);
      $query = $this->db->get();
      return $query->row();
    }
    
    
    /*-----------------------------------------
      コメントの取得（日付の新しい順・全て）
      ☆現在は仮で10件取得にしているが、おいおいはページング処理等を組込み、全てのデータが閲覧できるようにする
     -----------------------------------------*/
    function get_comments($fb_id){
      $this->db->select('comment,register_at');
      $this->db->from('learning2');
      $this->db->where('fb_id',$fb_id);
      $this->db->group_by('register_at');
      $this->db->order_by("register_at", "desc");
      $this->db->limit(10); //一時的に取得数に制限つき
      $query = $this->db->get();
      return $query->result();
    }
    
    //コメントと学習内容
    function com_and_content($fb_id){
      $sql = "SELECT l2.comment, l2.register_at, l.fl_content, l.sl_content, l.tl_content
              FROM learning2 l2
              INNER JOIN learning l
              USING ( fb_id ) 
              WHERE fb_id =  '{$fb_id}'
              AND l.register_at = l2.register_at
              GROUP BY l2.register_at
              ORDER BY l2.register_at DESC ";
      $query = $this->db->query($sql);
      return $query->result();
    }
    

    /*------------------------------------------ 
      学習計画の取得（最新のみ）
     -----------------------------------------*/
    function get_plans($fb_id){
      $this->db->select('nextplan,register_at');
      $this->db->from('learning2');
      $this->db->where('fb_id',$fb_id);
      $this->db->group_by('register_at');
      $this->db->order_by('register_at','desc');
      $this->db->limit(1);
      $query = $this->db->get();
      return $query->row();
    }
    
     /*------------------------------------------ 
      学習計画の取得（全て）
     -----------------------------------------*/
    function get_allplans($fb_id){
      $this->db->select('nextplan,register_at');
      $this->db->from('learning2');
      $this->db->where('fb_id',$fb_id);
      $this->db->group_by('register_at');
      $this->db->order_by('register_at','desc');
      $query = $this->db->get();
      return $query->result();
    }
       
    
    /*--------------------------------------------------------------------------------------------
      ユーザーが所属するグループのメンバーが設定している目標の取得（期限が切れていない目標のみ）
     --------------------------------------------------------------------------------------------*/
    function friend_goal($fb_id){
      $date = date('Y-m-d');
      $sql = "select g.fb_id,g.goal,g.period,u.fb_name 
              from goal g 
              left join user u 
              using(fb_id) 
              where fb_id 
              in (
                  select gm2.fb_id 
                  from group_member gm2 
                  where group_id 
                  in (
                      select gm.group_id 
                      from group_member gm 
                      where fb_id = '{$fb_id}')
                  ) 
              and g.period > '{$date}'
              and fb_id != '{$fb_id}';";
      $query = $this->db->query($sql);
      return $query->result();
    }
    
    /*--------------------------------------------------------------------------------------------
     * ユーザーが所属するグループ全てのメンバーそれぞれの最新の学習記録の取得
     --------------------------------------------------------------------------------------------*/   
    function get_allrecdata($fb_id){
      
      $sql = "select * from(
                SELECT u.fb_id, u.fb_name, l.fl_content, l.sl_content, l.tl_content, l.fl_time, l.sl_time, l.tl_time, l.total_time, l.register_at, l2.comment 
                FROM learning l
                INNER JOIN learning2 l2 
                ON l.total = l2.total
                INNER JOIN user u
                ON l.fb_id = u.fb_id
                WHERE l.group_id

                IN(
                   SELECT g.group_id
                   FROM learning l
                   INNER JOIN groups g ON l.group_id = g.group_id
                   WHERE fb_id = '{$fb_id}'
                   GROUP BY g.group_id
                   )
                ORDER BY l.register_at desc
              )b
              group by fb_id;";
              
      $query = $this->db->query($sql);
      return $query->result();
    }
    
    /*-----------------------------------
     * ユーザーが所属するグループの取得
     -----------------------------------*/
    function db_groups($fb_id){
      $this->db->select('g.group_id,g.group_name');
      $this->db->from('groups g');
      $this->db->join('group_member gm','g.group_id = gm.group_id','inner');
      $this->db->where('gm.fb_id',$fb_id);
      $query = $this->db->get();
      return $query->result();
    }

    /*-----------------------------------
     * 引数の日付の学習が記録されているか否か
     -----------------------------------*/
    function is_log($fb_id,$date){
      $query = $this->db->get_where('learning', array('fb_id' => $fb_id,'register_at' => $date));
      return $query->num_rows();
    }

  }
?>
