<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of facebook_model
 *
 * @author LowkeyMan
 */
class Facebook_model extends CI_Model {

    var $return_ssl_resources = false;
    
    public function __construct()
    {
        parent::__construct();
        
        $this->return_ssl_resources = is_https == 'https';
        
        $admins = $this->db->get('admin')->result_array();
        define('FBAPP_ID', $admins[0]['FBAPP_ID']);
        define('FBAPP_SECRET', $admins[0]['FBAPP_SECRET']);
        define('FBAPP_TITLE', $admins[0]['FBAPP_TITLE']);
        define('FBAPP_TITLE_TC', $admins[0]['FBAPP_TITLE_TC']);
        define('APP_HOST', is_https . "://apps.facebook.com/" . FBAPP_TITLE . "/");
        define('APP_HOST_HTTP', "http://apps.facebook.com/" . FBAPP_TITLE . "/");
        
        $config = array(
                        'appId'  => FBAPP_ID,
                        'secret' => FBAPP_SECRET,
                        'cookie' => true, // Indicates if the CURL based @ syntax for file uploads is enabled.
                        );
 
        $this->load->library('facebook', $config);
        $user = $this->facebook->getUser(); 
        // $fb_data = $this->session->userdata('fb_data');
        $profile = null;
        
        if($user)
        {
            try {
                // Proceed knowing you have a logged in user who's authenticated.
                $profile = $this->me();
            } catch (FacebookApiException $e) {
                $result = $e->getResult();
                error_log(json_encode($result));
                $user = null;
            }
        }

        $_SESSION['is_mobile'] = 'N';
        if (strstr($_SERVER['HTTP_USER_AGENT'], 'iPod') || stripos(strtolower($_SERVER['HTTP_USER_AGENT']), 'android') !== false) {
            $_SESSION['is_mobile'] = 'Y';
        }

        $fb_data = array(
                        'me' => $profile,
                        'uid' => $user,
                        'loginUrl' => $this->facebook->getLoginUrl(array(
                            'redirect_uri' => str_replace(WEB_HOST, APP_HOST, current_url()),
                            'scope' => SCOPE
                        )),
                        'logoutUrl' => $this->facebook->getLogoutUrl(),
                    );
        
        // $this->session->set_userdata('fb_data', $fb_data);
        $_SESSION['fb_data'] = $fb_data;
    }
    
    function me(){
        $fql = "SELECT uid,name FROM user WHERE uid=".$this->facebook->getUser();

        $param = array('method' => 'fql.query','query' => $fql,'callback' => '');
        $me = $this->facebook->api($param);
        return $me[0];
    }
    
    //po臉書塗鴉牆
    function po_wall($fb_ids = array(),$params = array()){
            
        $message = $params['message'];
        $description = $params['description'];
        $description_link = $params['description_link'];
        $attachment_name = $params['attachment_name'];
        $attachment_name_link = $params['attachment_name_link'];
        $attachment_text = $params['attachment_text'];
        $attachment_text_link = $params['attachment_text_link'];
        $img = $params['img'];
        $img_link = $params['img_link'];
        
        //get user name
        $fb_data = $this->session->userdata('fb_data');
        $uid = $this->facebook->getUser();
        $userName = $fb_data['me']['name'];
        
        //塗鴉牆附件參教
        $title = "想約 OOO 一起洗香香！";
        $data = array();
        
        
        
        //塗鴉牆附件參教
        $attachment = array(
            //"name"=>$title,
            "href"=>$attachment_name_link,  //標題
            "description"=>$attachment_text,    //說明
            "media"=> array(array("type" => "image","href" => $img_link,"src" => $img)),
            "properties"=>array(" " =>array("text"=>$attachment_text,"href"=>$attachment_text_link))
        );  //左邊圖片
        
        //塗鴉牆基本參數
        $param  =   array(
            "message" => $message,
            'method'  => 'stream_publish',
            'attachment'    => $attachment,
            'target_id'    => 0 , //friends fb id: 100000191677819
            'uid' => $uid //login user fb id
        );
        
        try
        {
            foreach($fb_ids as $fb_id):
                
                $param["target_id"] = $fb_id;
                
                //取得朋友姓名
                $friends = $this->get_friends_list("uid = $fb_id");
                foreach($friends as $row) $friend = $row['name'];
                
                    //附件裡的朋友名字要換
                    $attachment['description'] = $description;
                    $attachment['name'] = $attachment_name;
                    $param['attachment'] = $attachment;
                    
                    $data[] = array('uid'=>$uid,'fb_id'=>$fb_id);
                    
                    //print_r($param);
                    
                    //post to facebook!!!!!!!
                    $this->facebook->api($param);
            
                endforeach;
                //print_r($data);
        }
        catch(Exception $o){
            print_r($o);
            return;
        }
    }

    function getNotify($cnt){
        $fids = array();
        $friends_likeit = array();
        $rrr = $this->getNotificationFriendsIds();
        foreach($rrr as $rr){
            $friends_likeit[$rr['sender_id']] = 1;
        }
        
        $friends = array();
        $friends1 = array();
        $friends2 = array();

        if($friends_likeit && count($friends_likeit)>0){                            
            if(count($friends_likeit) > 0){
                $fids = array_keys($friends_likeit);
                shuffle($fids);
                
                $fids = array_slice($fids,0,$cnt);       
                // $friends1 = $this->facebook_model->getFriendsData($fids);
                foreach ($fids as $key => $value) {
                    $friends = $this->getUser($value);
                    $friends1[] = $friends[0];
                }
            }
            
            if(count($friends_likeit)<$cnt){
                $friends2 = $this->getMostFriendCountFriends($me_fb_id, $cnt-count($friends_likeit));
                foreach ($friends2 as $key => $value) {
                    $friends1[] = $value;
                }
            }
        }else{
            $friends1 = $this->getMostFriendCountFriends($me_fb_id, $cnt);
        }
        return $friends1;
    }

    function album($params = array()){
        $album_id = 0;
        $pic = $params['pic'];
        $albums = $this->facebook->api('/me/albums');
        $album_name = $params['album_name'];
        $album_description = $params['album_description'];
        $picture_description = $params['picture_description'];
        
//        printww
        
        foreach($albums['data'] as $album):
            if($album['name']==$album_name):
                $album_id = $album['id'];
                break;
            endif;
        endforeach;
        //if have no eat album, then careat it
        
        try{
	        if(!$album_id):
	            $album_details = array(
	                'name'   => $album_name,
	                'message'=> $album_description);
	            $create_album = $this->facebook->api('/me/albums', 'post', $album_details);
	            $album_id = $create_album['id'];
	        endif;
	        //echo $album_id;
	
	        $photo_details = array(
	            'url'  =>  $pic,
	            'message'=>  $picture_description
	        );
	
	        $this->facebook->setFileUploadSupport(true);
	        
	        
	        return $photo_obj = $this->facebook->api('/'.$album_id.'/photos', 'post', $photo_details);
        }catch(FacebookApiException $e){
        	$err = $e->getResult();
        	if($err['error']['code']=='2010'){
        		echo '此應用程式已被限制貼牆';
        		exit;
        	}
        }
    }

    function tag($params = array()){
        $upload_photo_id = $params['upload_photo_id'];
        $uid = $params['uid'];
        $x = $params['x'];
        $y = $params['y'];
        $tag_details = array('to'=> (string)$uid."", 'x'=>$x, 'y'=>$y);
        try{
            $tagged = $this->facebook->api('/'.$upload_photo_id.'/tags','post',$tag_details);
        } catch (FacebookApiException $e) {
            print_r($e);
        }
    }
    
    function get_friends_list($option = ''){
        ///user fb id
        $userid = $this->facebook->getUser();
        
        if($option=='') $option = "uid IN (SELECT uid2 FROM friend WHERE uid1 = $userid ) order by name";
        
        $fql = "SELECT uid,name,sex,pic_big FROM user WHERE $option";
        $param = array('method' => 'fql.query','query' => $fql,'callback' => '');
        return $this->facebook->api($param);
    }
    
    function getLikes( $uid = 'me' ){
    	$results =  $this->facebook->api($uid."/likes?locale=zh_TW");
    	if(!isset($results) || !isset($results['data'])){
    		return false;
    	}else{
    		return $results['data'];
    	}
    }

    function getLike($since,$until,$type){
        $since = $since==''?time()-(60*60*24*7):$since;
        $until = $until==''?time():$until;

        switch($type){
            case 'check-in':
                $fql = "SELECT checkin_id FROM checkin WHERE checkin_id in (SELECT id FROM object_url WHERE id IN (SELECT object_id FROM like WHERE user_id=me()) and type = '$type') and timestamp >= $since and timestamp <= $until";
            break;
            case 'status':
                $fql = "SELECT status_id FROM status WHERE status_id in (SELECT id FROM object_url WHERE id IN (SELECT object_id FROM like WHERE user_id=me()) and type = '$type') and time >=$since and time <= $until";
            break;
            case 'photo':
                $fql = "SELECT object_id FROM photo WHERE object_id in (SELECT id FROM object_url WHERE id IN (SELECT object_id FROM like WHERE user_id=me()) and type = '$type')";
            break;
            case 'album':
                $fql = "SELECT aid, owner, name, object_id,created FROM album WHERE object_id in (SELECT id FROM object_url WHERE id IN (SELECT object_id FROM like WHERE user_id=me()) and type = '$type') and created >= $since and created <= $until";
            break;
        }
        $fql = "SELECT id, type FROM object_url WHERE id IN (SELECT object_id FROM like WHERE user_id=me() ) limit 90000";
        
        $param = array('method' => 'fql.query','query' => $fql,'callback' => '');
        return $this->facebook->api($param);
    }

    public function getUser($uid){
        $fql = "SELECT uid,pic,name,pic_big,friend_count,email,sex FROM user where uid = $uid";
        $param = array('method' => 'fql.query','query' => $fql,'callback' => '');
        $user = $this->facebook->api($param);
        return $user[0];
    }

    function getNotificationFriendsIds(){
        $fql = "SELECT sender_id FROM notification WHERE recipient_id = ".$this->facebook->getUser()." AND sender_id IN (SELECT uid2 FROM friend WHERE uid1=".$this->facebook->getUser().") ORDER BY updated_time DESC";
        $param = array('method' => 'fql.query','query' => $fql,'callback' => '');
        return $this->facebook->api($param);        
    }   

    function getMostFriendCountFriends( $uid = 'me()', $limit = 1 ){    
        $fql = "SELECT uid,name,pic,pic_square,pic_big FROM user WHERE uid IN (SELECT uid2 FROM friend WHERE uid1='$uid') ORDER BY friend_count DESC LIMIT $limit";
        $params = array('method' => 'fql.query','query' => $fql,'callback' => '', 'return_ssl_resources'=>$this->return_ssl_resources);             
        $arr = $this->facebook->api($params);
        return isset($arr) && count($arr)>0 ? $arr : false ;
    }

    function getFriendsData($fids){
        $fql = "SELECT uid,name,pic,pic_square,pic_big FROM user WHERE uid IN (".  implode(",", $fids) .")";
        //echo  $fql;
        $params = array('method' => 'fql.query','query' => $fql,'callback' => '', 'return_ssl_resources'=>$this->return_ssl_resources);        
        return $this->facebook->api($params);
    }

    function getAPPTitle(){
        $fql = "select display_name from application where app_id = ".FBAPP_ID;
        $params = array('method' => 'fql.query','query' => $fql,'callback' => '');
        
        return $this->facebook->api($params);
    }

    public function getAlbumList(){
        $fql = "SELECT src_big,object_id,album_object_id FROM photo WHERE object_id in (SELECT cover_object_id FROM album 
        where owner = me()) order by created desc";
        $param = array('method' => 'fql.query','query' => $fql,'callback' => '', 'return_ssl_resources' => $this->return_ssl_resources);
        return $this->facebook->api($param);
    }
}

?>