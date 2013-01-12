<?php
  class Group_model extends CI_Model{
    
    function __construct(){
      parent::__construct();
      $this->load->database();
      $this->fbauth = $this->config->item('fbauth');
      $appId = $this->fbauth['appId'];
      $secret = $this->fbauth['secret'];
      $fb_params = array('appId' => $appId, 'secret' => $secret);
      $this->load->library('facebook',$fb_params);
    } 
    
    /********************************************
     * グループが既に登録されているかどうかの検証
     ********************************************/
    function is_group($group_id){
      $query = $this->db->get_where('groups', array('group_id' => $group_id));
      return $query->num_rows();
    }
    
    
    /******************************
     * グループテーブルへのDB登録
     ******************************/
    function group_register($group_id){
      //グループ名の取得
      $groupinfo = $this->facebook->api($group_id);
      $group_name = $groupinfo['name'];
      $data = array('group_id'=>$group_id,'group_name'=>$group_name);
      $this->db->insert('groups',$data);
    }
    
    /******************************
     * グループテーブルへのDB登録その２
     ******************************/
    function group_register2($group_id,$group_name){
      $data = array('group_id'=>$group_id,'group_name'=>$group_name);
      $this->db->insert('groups',$data);
    }
    
    
    
    /****************************************************************************************
     * 学習を記録したユーザーが、投稿先のグループメンバーとして既に登録されているかどうかの検証
     ****************************************************************************************/
    function is_member($fb_id,$group_id){
      $query = $this->db->get_where('group_member',array('fb_id'=>$fb_id, 'group_id'=>$group_id));
      return $query->num_rows();
    }
    
    
    /*************************************
     * グループメンバーテーブルへのDB登録
     *************************************/
    function member_register($fb_id,$group_id){
      $data = array('fb_id'=>$fb_id,'group_id'=>$group_id);
      $this->db->insert('group_member',$data);
    }
    
    
    /*************************************************
     * ユーザーが学習記録を共有しているグループの取得
     *************************************************/
    function get_activegroups($fb_id){
      $sql = "select g.group_name,g.group_id 
              from learning l 
              inner join groups g 
              on l.group_id = g.group_id 
              where fb_id = '{$fb_id}' 
              group by g.group_id;";
      
      $query = $this->db->query($sql);
      return $query->result();
    }
    
    /*************************************************
     * ユーザーが学習記録を共有しているグループの取得
     *************************************************/
    function get_groupid($fb_id){
      $groupinfo = $this->facebook->api('/'.$fb_id.'/groups');
      $groupids = array();
      foreach($groupinfo as $data){
        $groupids[] = $data;
      }
      
      return $groupids;
    }    
    
    
    /*************************************************
     * ユーザーが学習記録を共有しているグループIDの取得
     *************************************************/
    function get_activegroupsId($fb_id){
      $sql = "select g.group_id 
              from learning l 
              inner join groups g 
              on l.group_id = g.group_id 
              where fb_id = '{$fb_id}' 
              group by g.group_id;";
              
      $query = $this->db->query($sql);
      return $query->result();
    }    
    /****************************************
     * アクティブなグループ内メンバー情報の取得
     ****************************************/
    function get_activemember($group_id){
      $sql = "select gm.fb_id,u.fb_name,u.started_at
              from group_member gm 
              inner join user u 
              using(fb_id)  
              where group_id = '{$group_id}';";
              
      $query = $this->db->query($sql);
      return $query->result();
    }
    

    
    
    
    /************************************************
     * グループメンバーそれぞれの最新の学習記録の取得（指定したグループのみ）
     ************************************************/
    function get_grouplearning($group_id){      
      $sql = "select * 
              from 
              (select u.fb_id,u.fb_name,l.fl_content,l.sl_content,l.tl_content,l.fl_time,l.sl_time,l.tl_time,l.total_time,l.register_at 
              from learning l 
              inner join user u 
              on l.fb_id = u.fb_id 
              where group_id = '{$group_id}' 
              order by l.register_at desc)a 
              group by fb_id;";
                 /*
      $sql = "select * 
              from 
              (select u.fb_id,u.fb_name,l.fl_content,l.sl_content,l.tl_content,l.fl_time,l.sl_time,l.tl_time,l.total_time,l.register_at,l2.comment 
              from learning l 
              inner join 
              user u 
              on l.fb_id = u.fb_id 
              inner join learning2 l2 
              on l.total = l2.total 
              where l.group_id = '{$group_id}' 
              order by register_at desc)a 
              group by fb_id;";
                  */
              
              //コメントが最新データじゃない。。

      $query = $this->db->query($sql);
      return $query->result();
    }
    
    
    /*************************************************
     * グループメンバーの学習時間の総計（全記録・降順）
     *************************************************/
    function sum_alltime($group_id){
      //ここで取得するのはそのグループに対して投稿した学習記録のみ（他のグループに対して投稿したものは取得しない）
      $sql = "select u.fb_name,u.fb_id,sum(l.total_time) as time 
              from learning l 
              inner join user u 
              using(fb_id) 
              where group_id='{$group_id}' 
              group by l.fb_id 
              order by sum(l.total_time) desc;";
      
      $query = $this->db->query($sql);
      return $query->result();
    }
    
    /*****************************************************
     * グループメンバーの学習時間の総計（直近一週間・降順）
     *****************************************************/
    function sum_weektime($group_id){
      $to = date('Y-m-d');
      $from = date('Y-m-d',mktime(0,0,0,date('m'),date('d')-7,date('Y')));
                    
      $sql = "select u.fb_name,u.fb_id,sum(l.total_time) as time 
              from learning l 
              inner join user u 
              using(fb_id) 
              where group_id='{$group_id}' 
              and '{$to}' >= l.register_at 
              and l.register_at >= '{$from}' 
              group by l.fb_id 
              order by sum(l.total_time) desc;";
              
      $query = $this->db->query($sql);
      return $query->result();
    }
    
    
    /*****************************************************
     * グループメンバーの学習時間の総計（直近3日間・降順）
     *****************************************************/
    function sum_recenttime($group_id){
      $to = date('Y-m-d');
      $from = date('Y-m-d',mktime(0,0,0,date('m'),date('d')-3,date('Y')));
                    
      $sql = "select u.fb_name,u.fb_id,sum(l.total_time) as time 
              from learning l 
              inner join user u 
              using(fb_id) 
              where group_id='{$group_id}' 
              and '{$to}' >= l.register_at 
              and l.register_at >= '{$from}' 
              group by l.fb_id 
              order by sum(l.total_time) desc;";
              
      $query = $this->db->query($sql);
      return $query->result();
    }
    
    
    /***************************************************
     * グループメンバーの学習時間の平均（全記録・降順）
     ***************************************************/
    function avg_alltime($group_id){
      $sql = "select u.fb_name,u.fb_id,avg(l.total_time) as time 
              from learning l 
              inner join user u 
              using(fb_id) 
              where group_id='{$group_id}' 
              group by l.fb_id 
              order by avg(l.total_time) desc;";
      
      $query = $this->db->query($sql);
      return $query->result();      
    }
    
    /*****************************************************
     * グループメンバーの学習時間の平均（直近一週間・降順）
     *****************************************************/
    function avg_weektime($group_id){
      $to = date('Y-m-d');
      $from = date('Y-m-d',mktime(0,0,0,date('m'),date('d')-7,date('Y')));
                    
      $sql = "select u.fb_name,u.fb_id,avg(l.total_time) as time 
              from learning l 
              inner join user u 
              using(fb_id) 
              where group_id='{$group_id}' 
              and '{$to}' >= l.register_at 
              and l.register_at >= '{$from}' 
              group by l.fb_id 
              order by avg(l.total_time) desc;";
              
      $query = $this->db->query($sql);
      return $query->result();
    }
    
    /*****************************************************
     * グループメンバーの学習時間の平均（直近3日間・降順）
     *****************************************************/
    function avg_recenttime($group_id){
      $to = date('Y-m-d');
      $from = date('Y-m-d',mktime(0,0,0,date('m'),date('d')-3,date('Y')));
                    
      $sql = "select u.fb_name,u.fb_id,avg(l.total_time) as time 
              from learning l 
              inner join user u 
              using(fb_id) 
              where group_id='{$group_id}' 
              and '{$to}' >= l.register_at 
              and l.register_at >= '{$from}' 
              group by l.fb_id 
              order by avg(l.total_time) desc;";
              
      $query = $this->db->query($sql);
      return $query->result();
    }
    
    
    /*************************************************************
     * グループメンバーの学習時間の合計と平均（全期間・合計の降順）
     *************************************************************/    
    function all_lt($group_id){
      $sql = "select u.fb_name,u.fb_id,sum(l.total_time) as s_time,avg(l.total_time) as a_time
              from learning l 
              inner join user u 
              using(fb_id) 
              where group_id='{$group_id}' 
              group by l.fb_id 
              order by sum(l.total_time) desc;";
 
      $query = $this->db->query($sql);
      return $query->result();
    }
    
    /*************************************************************
     * グループメンバーの学習時間の合計と平均（直近3日間・合計の降順）
     *************************************************************/    
    function week_lt($group_id){
      $to = date('Y-m-d');
      $from = date('Y-m-d',mktime(0,0,0,date('m'),date('d')-7,date('Y')));
                    
      $sql = "select u.fb_name,u.fb_id,sum(l.total_time) as s_time,avg(l.total_time) as a_time  
              from learning l 
              inner join user u 
              using(fb_id) 
              where group_id='{$group_id}' 
              and '{$to}' >= l.register_at 
              and l.register_at >= '{$from}' 
              group by l.fb_id 
              order by sum(l.total_time) desc;";
              
      $query = $this->db->query($sql);
      return $query->result();
    }
    
    /*************************************************************
     * グループメンバーの学習時間の合計と平均（直近3日間・合計の降順）
     *************************************************************/    
    function recent_lt($group_id){
      $to = date('Y-m-d');
      $from = date('Y-m-d',mktime(0,0,0,date('m'),date('d')-3,date('Y')));
                    
      $sql = "select u.fb_name,u.fb_id,sum(l.total_time) as s_time,avg(l.total_time) as a_time  
              from learning l 
              inner join user u 
              using(fb_id) 
              where group_id='{$group_id}' 
              and '{$to}' >= l.register_at 
              and l.register_at >= '{$from}' 
              group by l.fb_id 
              order by sum(l.total_time) desc;";
              
      $query = $this->db->query($sql);
      return $query->result();
    }
    
    
    /*****************************************************
     * グループメンバーの自己評価の平均（全期間・降順）
     *****************************************************/
    function allsr($group_id){
      //sr = selfreview
      $sql = "select u.fb_name,u.fb_id,avg(l.selfreview) as sr 
              from learning2 l 
              inner join user u 
              using(fb_id) 
              where group_id='{$group_id}' 
              group by l.fb_id 
              order by avg(l.selfreview) desc;";
      
      $query = $this->db->query($sql);
      return $query->result();      
    }
    

    /*****************************************************
     * グループメンバーの自己評価の平均（全期間・降順）
     *****************************************************/
    function weeksr($group_id){
      $to = date('Y-m-d');
      $from = date('Y-m-d',mktime(0,0,0,date('m'),date('d')-7,date('Y')));
                    
      $sql = "select u.fb_name,u.fb_id,avg(l.selfreview) as sr 
              from learning2 l 
              inner join user u 
              using(fb_id) 
              where group_id='{$group_id}' 
              and '{$to}' >= l.register_at 
              and l.register_at >= '{$from}' 
              group by l.fb_id 
              order by avg(l.selfreview) desc;";
              
      $query = $this->db->query($sql);
      return $query->result();      
    }
    
    
    /*****************************************************
     * グループメンバーの自己評価の平均（直近3日間・降順）
     *****************************************************/
    function recentsr($group_id){
      $to = date('Y-m-d');
      $from = date('Y-m-d',mktime(0,0,0,date('m'),date('d')-3,date('Y')));
                    
      $sql = "select u.fb_name,u.fb_id,avg(l.selfreview) as sr 
              from learning2 l 
              inner join user u 
              using(fb_id) 
              where group_id='{$group_id}' 
              and '{$to}' >= l.register_at 
              and l.register_at >= '{$from}' 
              group by l.fb_id 
              order by avg(l.selfreview) desc;";
              
      $query = $this->db->query($sql);
      return $query->result();      
    }
    
    
    /***********************************************
     * グループメンバー全員の学習時間の合計（全期間）
     ***********************************************/
    function sum_grouplt($group_id){
      //lt = learnig time
      $sql = "select g.group_name,sum(total_time) as total 
              from learning l 
              inner join groups g 
              using(group_id) 
              where group_id = '{$group_id}';";

      $query = $this->db->query($sql);
      return $query->row();      
      //select g.group_name,sum(total_time) as total from learning l inner join groups g using(group_id) where group_id = '295509303869921';
    }
    
    
    /**********************************************
     * グループメンバー全員の学習時間の合計の内訳
     **********************************************/
    function detail_grouplt($group_id){
      $sql ="select u.fb_name,sum(total_time) as total 
             from learning l 
             inner join user u 
             using(fb_id) 
             where group_id = '{$group_id}' 
             group by l.fb_id;";

      $query = $this->db->query($sql);
      return $query->result();      
      //select u.fb_name,sum(total_time) as total from learning l inner join user u using(fb_id) where group_id = '295509303869921' group by l.fb_id;
    }
    
    
    /****************************************
     * グループメンバー各々の最新の学習予定
     ****************************************/
    function le_plan($group_id){
      //fb_name、fb_id、nextplan、register_at
      $sql = "select * 
              from 
              (select u.fb_id,u.fb_name,l.register_at,l.nextplan 
              from learning2 l 
              inner join user u 
              using(fb_id) 
              where group_id = '{$group_id}' 
              order by l.register_at desc)a 
              group by fb_id;";

      $query = $this->db->query($sql);
      return $query->result();
    }
    
    
    /****************************************
     * 学習記録へのコメントの登録
     ****************************************/
    function insert_comment($comment,$from_id,$to_id,$group_id,$log_id){
      $data = array('log_id'=>$log_id,
                    'to_id'=>$to_id,
                    'from_id'=>$from_id,
                    'comment'=>$comment,
                    'group_id'=>$group_id, 
                    'postedat'=>date('Y-m-d H:i:s')
                    );
      
      $this->db->insert('post_com',$data);
    }
    
    
    /****************************************
     * 学習記録へのいいねの登録
     ****************************************/
    function insert_like($from_id,$to_id,$group_id,$log_id){
      $data = array('from_id'=>$from_id,
                    'to_id'=>$to_id,
                    'group_id'=>$group_id, 
                    'log_id'=>$log_id,
                    'liked_at'=>date('Y-m-d H:i:s')
                    );
      
      $this->db->insert('post_like',$data);
    }

    /****************************************
     * 学習記録へのコメントの取得
     ****************************************/
    //これも使わない？
    function get_comment($group_id){
      $this->db->select('log_id,comment,postedat');
      $this->db->from('post_com');
      $this->db->where('group_id',$group_id);
      $query = $this->db->get();
      return $query->result();
    }
    
    
    //使わない
    function get_logids($group_id){
      $this->db->select('log_id');
      $this->db->from('post_com');
      $this->db->where('group_id',$group_id);
      $query = $this->db->get();
      return $query->result_array();
    }
    
    
    //コメントの抽出
    function select_com($log_id){      
      /*to_idを付け加えた*/
      $this->db->select('post_com.comment,post_com.to_id,post_com.from_id,post_com.log_id,post_com.comment_id,post_com.postedat,user.fb_name');
      $this->db->from('post_com');
      $this->db->join('user', 'post_com.from_id = user.fb_id', 'inner');
      $this->db->where('log_id',$log_id);
      $this->db->where('status','1');
      $query = $this->db->get();
      return $query->result();      
    }
    
    //いいねの抽出
    function select_like($log_id){
      $this->db->select('post_like.like_id,post_like.liked_at,post_like.from_id,user.fb_name');
      $this->db->from('post_like');
      $this->db->join('user', 'post_like.from_id = user.fb_id', 'inner');
      $this->db->where('log_id',$log_id);
      $this->db->where('status','1');
      $query = $this->db->get();
      return $query->result();      
    }
    
    
    //コメントの削除
    function drop_com($comment_id){
      $data = array('status' => '0');
      $this->db->where('comment_id', $comment_id);
      $this->db->update('post_com', $data); 
    }
    
    //いいねの削除（物理削除）
    function drop_like($like_id){
      $this->db->delete('post_like', array('like_id' => $like_id)); 
    }
    
    //LIKE_IDの取得
    function get_likeid($group_id,$log_id){
      $this->db->select('like_id');
      $this->db->from('post_like');
      $this->db->where('group_id',$group_id);
      $this->db->where('log_id',$log_id);
      $query = $this->db->get();
      return $query->row();
    }
    
    //いいねを押しているか否かを確かめる
    function is_like($log_id,$group_id,$fb_id){
      $query = $this->db->get_where('post_like', 
                                     array('log_id' => $log_id,
                                           'group_id' => $group_id,
                                           'from_id' => $fb_id)
                                   );
      return $query->num_rows();      
    }
    
    //既に「いいね！」をしているユーザーの取得
    function get_likers($group_id, $log_id){
      $sql = "select u.fb_name 
              from post_like pl 
              inner join user u 
              on pl.from_id = u.fb_id 
              where log_id = '{$log_id}' 
              and group_id = '{$group_id}';";
      
      $query = $this->db->query($sql);
      return $query->result();
    }

    //コメントIDの取得（ただし汎用性は無いので利用する際には注意）
    function get_comid($group_id,$log_id,$from_id,$to_id){
      $this->db->select('comment_id');
      $this->db->from('post_com');
      $this->db->where('group_id',$group_id);
      $this->db->where('log_id',$log_id);
      $this->db->where('from_id',$from_id);
      $this->db->where('to_id',$to_id);
      $this->db->order_by('comment_id','desc');
      $query = $this->db->get();
      return $query->row();
    }
    
    
    /****************************************
     * グループ一番の努力家の取得
     ****************************************/
    function top_hard_worker($group_id){
      $sql = "select l.fb_id,sum(l.total_time),u.fb_name 
              from learning l 
              inner join user u using(fb_id) 
              where group_id='{$group_id}' 
              group by fb_id 
              order by sum(l.total_time) 
              desc limit 1;";
      $query = $this->db->query($sql);
      return $query->row();
    }
    
    
    /****************************************
     * グループ一番の安定感の取得
     ****************************************/
    function top_stability($group_id){
      $sql ="select l.fb_id,avg(l.total_time),u.fb_name 
             from learning l 
             inner join user u using(fb_id) 
             where group_id='{$group_id}' 
             group by fb_id 
             order by avg(l.total_time) 
             desc limit 1;";
      $query = $this->db->query($sql);
      return $query->row();
    }
    
    
    /****************************************
     * グループ一番の学習満足度
     ****************************************/
    function top_satisfaction($group_id){
      $sql ="select l.fb_id,avg(l.selfreview),u.fb_name 
             from learning2 l 
             inner join user u using(fb_id) 
             where group_id='{$group_id}' 
             group by fb_id 
             order by avg(l.selfreview) 
             desc limit 1;";
      $query = $this->db->query($sql);
      return $query->row();
    }
    
    /*--------------------------------------
      グループメンバーの学習目標を取得する
     ---------------------------------------*/
    function member_goals($group_id){
      $date = date('Y-m-d');

      $sql = "select * from
                (select u.fb_name,u.picture,g.goal,g.period,gm.group_id,DATEDIFF(g.period,'{$date}') as diff 
                 from goal g 
                 inner join user u 
                 using(fb_id) 
                 inner join group_member gm 
                 using(fb_id)) a 
              where group_id ='{$group_id}'
              and period > '{$date}';";
      $query = $this->db->query($sql);
      return $query->result();
    }    
    
    
  }
?>