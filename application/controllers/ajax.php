<?php
  class Ajax extends CI_Controller {
    
    public function __construct(){
      parent::__construct();      
      $this->fbauth = $this->config->item('fbauth');
      $appId = $this->fbauth['appId'];
      $secret = $this->fbauth['secret'];
      $fb_params = array('appId' => $appId, 'secret' => $secret);
      $this->load->library('facebook',$fb_params);
    }
    
    
   /****************************************
    * リアルタイムで学習の共有
    ****************************************/
    public function mini_share(){
      $groupid = $_POST['groupid'];
      $btnid = $_POST['btnid'];
      $fb_name = $_POST['fb_name'];
      $date = arrange_date2(date('Y-m-d H:i:s'));
      
      
      switch ($btnid) {
        case 1:
          $text = "{$fb_name}さんが学習状況をシェアしました。
                   「今から勉強します(`・ω・´)」";
          break;
        case 2:
          $text = "{$fb_name}さんが学習状況をシェアしました。
                   「休憩するよ(・ω・;)」";
          break;
        case 3:
          $text = "{$fb_name}さんが学習状況をシェアしました。
                   「今日はおしまい( ´Д｀)=3」";
          break;
        case 4:
          $text = "{$fb_name}さんが学習状況をシェアしました。
                   「しんどいよ〜(((ﾟДﾟ)))」";
          break;
        case 5:
          $text = "{$fb_name}さんが学習状況をシェアしました。
                   「今から本気出す(# ﾟДﾟ)」";
          break;
        case 6:
          $text = "{$fb_name}さんが学習状況をシェアしました。
                   「明日から本気出す(A` )」";
          break;        
        default:
          $text = "ウォールへ投稿！";
      }
      
      //ユーザーの所属するグループIDを取得
      $fb_id = $this->session->userdata('fb_id');
      $groupinfo = $this->facebook->api('/'.$fb_id.'/groups');      
      $i = 0;
      foreach($groupinfo as $data){
        $groupids[] = $data[$i]['id'];
      }
      
      //自分の所属するアクティブグループIDが選択されている場合のみ投稿処理をする
      if(isset($groupid) && in_array($groupid,$groupids)){
        $wallpost = $this->facebook->api("{$groupid}/feed",'post',array('message'=>$text));
        return $wallpost;
      }
      
    }
    
    
   /****************************************
    * 学習記録へのコメント
    ****************************************/
    public function post_comment(){
      $comment = $_POST['comment'];
      $from_id = $_POST['from_id'];
      $to_id = $_POST['to_id'];
      $group_id = $_POST['group_id'];
      $log_id = $_POST['log_id'];
      $date = arrange_date2(date('Y-m-d H:i:s'));
      
      //コメントのDB登録
      $this->Group_model->insert_comment($comment,$from_id,$to_id,$group_id,$log_id);
      
      //コメントIDの取得
      $comment_id = $this->Group_model->get_comid($group_id,$log_id,$from_id,$to_id);
      //要するに、remove_comの引数に新しく投稿したコメントのcomment_idとto_idとlog_idを取得する必要があるということ！
      
      
      //ユーザー名の取得
      $name = $this->User_model->get_name($from_id);
      
      //出力するデータの形成
      $result = "<div class='com-box clearfix'>
                   <div class='com-box-photo'>
                     <img src='https://graph.facebook.com/{$from_id}/picture' width='30px' height='30px' />
                   </div>
                   <div class='com-box-data'>
                     <p><strong>{$name->fb_name}</strong>   {$comment}</p>
                     <span>{$date}に投稿</span>
                     <a onclick=remove_com('{$comment_id->comment_id}','{$to_id}','{$log_id}')>コメントを削除する</a>
                   </div>
                 </div>";
      //<a onclick=remove_com('{$data->comment_id}','{$to_id}')>コメントを削除する</a>
      //これ必要なんだけど、comment_idが取得できていないので表示できない状態
                     
      echo $result;
    }

    
   /****************************************
    * 学習記録へのコメントの出力
    ****************************************/    
    public function output_com(){
      $log_id = $_POST['log_id'];
      $fb_id = $_POST['fb_id'];
      $to_id = substr($log_id,0,15);
      $result = $this->Group_model->select_com($log_id);
      
      foreach($result as $data){
        if($fb_id == $data->from_id){
                         
          $comments = "<div class='com-box clearfix'>
                         <div class='com-box-photo'>
                           <img src='https://graph.facebook.com/{$data->from_id}/picture' width='30px' height='30px' />
                         </div>
                         <div class='com-box-data'>
                           <p><strong>{$data->fb_name}</strong>   {$data->comment}</p>
                           <span>".arrange_date2($data->postedat)."に投稿  </span>
                           <a onclick=remove_com('{$data->comment_id}','{$to_id}','{$data->log_id}')>コメントを削除する</a>
                         </div>
                       </div> ";
          echo $comments;
        }else{
          $comments = "<div class='com-box clearfix'>
                         <div class='com-box-photo'>
                           <img src='https://graph.facebook.com/{$data->from_id}/picture' width='30px' height='30px' />
                         </div>
                         <div class='com-box-data'>
                           <p><strong>{$data->fb_name}</strong>   {$data->comment}</p>
                           <span>".arrange_date2($data->postedat)."に投稿  </span>
                         </div>
                       </div> ";
                           
          echo $comments;
        }
      }
      
    }
    
    
   /****************************************
    * 学習記録へのコメントの削除
    ****************************************/   
    function remove_com(){
      $comment_id = $_POST['comment_id'];
      $log_id = $_POST['log_id'];
      $fb_id = $_POST['fb_id'];
      $to_id = $_POST['to_id'];
      
      //コメントの削除処理
      $this->Group_model->drop_com($comment_id);      
      
      //コメントの取得処理
      $result = $this->Group_model->select_com($log_id);
      
      //コメントの出力処理
      
      /*編集中 2012-06-13 20:06 $data->log_idをいじった*/
      
      foreach($result as $data){
        if($fb_id == $data->from_id){
          $comments = "<div class='com-box clearfix'>
                         <div class='com-box-photo'>
                           <img src='https://graph.facebook.com/{$data->from_id}/picture' width='30px' height='30px' />
                         </div>
                         <div class='com-box-data'>
                           <p><strong>{$data->fb_name}</strong>   {$data->comment}</p>
                           <span>".arrange_date2($data->postedat)."に投稿  </span>
                           <a onclick=remove_com('{$data->comment_id}','{$to_id}','{$data->log_id}')>コメントを削除する</a>
                         </div>
                       </div> ";
          echo $comments;
        }else{
          $comments = "<div class='com-box clearfix'>
                         <div class='com-box-photo'>
                           <img src='https://graph.facebook.com/{$data->from_id}/picture' width='30px' height='30px' />
                         </div>
                         <div class='com-box-data'>
                           <p><strong>{$data->fb_name}</strong>   {$data->comment}</p>
                           <span>".arrange_date2($data->postedat)."に投稿  </span>
                         </div>
                       </div> ";
                           
          echo $comments;
        }
      }
    }
    
    
   /****************************************
    * 学習記録へのいいね
    ****************************************/    
    public function post_like(){
      $from_id = $_POST['from_id'];
      $to_id = $_POST['to_id'];
      $group_id = $_POST['group_id'];
      $log_id = $_POST['log_id'];
      
      //既にいいねしているユーザーの取得
      $likers = $this->Group_model->get_likers($group_id,$log_id);
      $liker_data = '';
      if($likers){
        foreach($likers as $liker){
          $liker_data .= "、{$liker->fb_name}さん";
        }
      }

      //いいねのDB登録
      $this->Group_model->insert_like($from_id,$to_id,$group_id,$log_id);
      
      //ユーザー名の取得
      $name = $this->User_model->get_name($from_id);      
      
      //出力するデータの形成
      if($liker_data){        
        $like = "<div class='like-box' style='padding:5px;'>
                   <span>
                     <i class='icon-thumbs-up'></i>
                     {$name->fb_name}さん{$liker_data}がいいね!と言っています。
                   </span>
                 </div>";
      }else{
        $like = "<div class='like-box' style='padding:5px;'>
                   <span>
                     <i class='icon-thumbs-up'></i>
                     {$name->fb_name}さんがいいね!と言っています。
                   </span>
                 </div>";
      }
      
      echo $like;      
    }

    
   /****************************************
    * 学習記録へのいいねの出力
    ****************************************/
    public function output_like(){
      $log_id = $_POST['log_id'];
      $fb_id = $_POST['fb_id'];
      $to_id = substr($log_id,0,15);
      $group_id = $_POST['group_id'];
      $result = $this->Group_model->select_like($log_id);

      //既にいいねしているユーザーの取得
      $likers = $this->Group_model->get_likers($group_id,$log_id);
      
      $liker_data = (string)'';
      
      if($likers != ''){
        foreach($likers as $liker){
          $liker_data .= "、{$liker->fb_name}さん";         
        }
        $liker_data = mb_substr($liker_data,1);
      }
      
      if($liker_data){
        $likes = "<div class='like-box' style='padding:5px;'>
                   <span><i class='icon-thumbs-up'></i> {$liker_data}がいいね!と言っています。</span>
                 </div>";
      }else{
        $likes = "<div class='like-box'></div>";
      }

      echo $likes;
      
      /*
      foreach($result as $data){
        if($fb_id == $data->from_id){
          //閲覧者 = 投稿者
          $likes = "<div>
                      <span>{$data->fb_name}さんがいいねと言っています。( {$data->liked_at} )</span>
                      <a onclick=remove_like('{$data->like_id}','{$to_id}')>いいねの削除</a>
                    </div>";
          echo $likes;
        }else{
          //その他
          $likes = "<div>
                      <span>{$data->fb_name}さんがいいねと言っています。( {$data->liked_at} )</span>
                    </div>";
          echo $likes;
        }
      }
      */
    }
    
   /****************************************
    * 学習記録へのいいねの削除
    ****************************************/  
    function remove_like(){
      $group_id = $_POST['group_id'];
      $log_id = $_POST['log_id'];
      
      //LIKE_IDの取得
      $result = $this->Group_model->get_likeid($group_id,$log_id);
      $like_id = $result->like_id;
      
      $this->Group_model->drop_like($like_id);
      
      //既にいいねしているユーザーの取得
      $likers = $this->Group_model->get_likers($group_id,$log_id);
            
      //いいねユーザーデータの整形
      $liker_data = '';
      if($likers){
        foreach($likers as $liker){
          $liker_data .= "、{$liker->fb_name}さん";
        }
        $liker_data = mb_substr($liker_data,1);
      }
      
      //出力するデータの形成
      if($liker_data){        
        $like = "<div class='like-box' style='padding:5px;'>
                   <span>
                     <i class='icon-thumbs-up'></i>
                     {$liker_data}がいいね!と言っています。
                   </span>
                 </div>";
      }else{
        //いいねをしているユーザーが0人になった場合
        $like = "<div class='like-box'>
                 </div>";
      }
      
      echo $like;
      
    }    

    
    
    /****************************************
    * いいねボタンの生成
    ****************************************/  
    function like_btn(){
      $log_id = $_POST['log_id'];
      $fb_id = $_POST['fb_id']; //要するに閲覧者のFacebookID
      $to_id = $_POST['to_id'];
      $group_id = $_POST['group_id'];
      $date = $_POST['date'];
      
      //いいねチェック
      $result = $this->Group_model->is_like($log_id,$group_id,$fb_id);
      
      if($result > 0){
        //いいね削除ボタン
        echo "<button class='not_like btn btn-danger btn-mini' onclick=\"remove_like('{$fb_id}','{$to_id}','{$date}')\" ><i class='icon-remove'></i> いいねを取り消す</button>";
                
      }else if($result == 0){
        //いいねボタン
        echo "<button class='like btn btn-primary btn-mini' onclick=\"post_like('{$fb_id}','{$to_id}','{$date}')\" ><i class='icon-thumbs-up'></i> いいね！</button>";
      }
    }
    
    
  }
?>
