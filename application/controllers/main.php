<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Main extends CI_Controller {

	function __construct() {
		parent::__construct();
		// $this->load->library('gd_creater');
		// $this->gd_creater->delimg();
		$this->load->helpers(['my_md5','age']);
	}
	
	private function _getBaseData(){
		$fansInfoArray = $this->getFansInfo();
		$data = array(
				'fb_title' => $this->APPTITLE(),
				'page_id' => $fansInfoArray['page_id'],
				'page_url' => $fansInfoArray['page_url'],
				'images' => $this->preload_images()
		);
		return $data;
	}

	public function index() {
// 		var_dump($_GET['t']);exit;
		$data = $this->_getBaseData();
// 		$friends = $this->facebook_model->get_friends_list();
// 		$fids = array();
// 		foreach($friends as $fk=>$fv){
// 			$fids[] = $fv['uid'];
// 		}
// 		echo (implode(',',$fids));

		$this->init_model->apply_template_with_ga($this->router->method . '_view', $data);
	}

	public function check_user($echo=true,$fbid=0){
		if(!empty($_POST['fbid'])):
			$fbid = $_POST['fbid'];
		elseif($fbid==0):
			$fbid = $this->facebook->getUser();
		endif;

		$user = $this->facebook_model->getUser($fbid);

		if($user['sex']=='male'):
			$params = array(
				'fbid_d' => $fbid
				);
		elseif($user['sex']=='female'):
			$params = array(
				'fbid_m' => $fbid
				);
		endif;
		$success = false;
		$rs = $this->baby_infov_md->get_one($params);
		if(sizeof($rs)>0):
			$success = true;
		endif;
		$json = array(
			'success' => $success
			);
		if($echo):
			echo json_encode($json);
		else:
			return $rs;
		endif;
	}

	public function guid(){
		// $str = '100000289183379'.time();
		// $md5 = str_md5($str);
		// echo $md5;
		
		$age = getAge('2012-05-17');
		var_dump($age);
	}

	public function notification($msg,$val,$fbid){
		$params = array(
            'access_token'=>FBAPP_ID.'|'.FBAPP_SECRET,
            'href'=>'?'.$val,
            'template'=>$msg);
        $sendnotification = $this->facebook->api('/'.$fbid.'/notifications', 'post', $params);
        exit;
	}

	/**
	 * [check_friend 自動加好友]
	 * @param  [type] $sid [description]
	 * @return [type]      [description]
	 */
	public function check_friend($sid){
		$fbid = $this->facebook->getUser();
		$friends = $this->facebook_model->get_friends_list();
		$fri_params = array();
		foreach($friends as $fk=>$fv):
			if($fv['sex']=='male'):
				$params = array(
					'fbid_d' => $fv['uid']
					);
			elseif($fv['sex']=='female'):
				$params = array(
					'fbid_m' => $fv['uid']
					);
			endif;
			$rs = $this->baby_info_md->get_one($params);
			if(sizeof($rs)>0):
				$this->notification('加好友通知','t=1',$rs['serial_id']);
				$params = array(
					'a_baby' => $sid,
					'b_baby' => $rs['serial_id']
					);
				$rs2 = $this->friend_info_md->getData($params);
				if(sizeof($rs2)==0):
					$fri_params[] = $params;
					$params = array(
						'b_baby' => $sid,
						'a_baby' => $rs['serial_id']
						);
					$fri_params[] = $params;
				endif;
			endif;
		endforeach;
		if(sizeof($fri_params)>0):
			$this->friend_info_md->insert_batch($fri_params);
		endif;
	}

	/**
	 * [getfriend description]
	 * @param  integer $sid [description]
	 * @return [type]       [description]
	 */
	public function getfriend($sid=0){
		$params = array(
			'a_baby' => $sid
			);
		$rs = $this->friend_info_md->getData($params);
		$friends = array();
		foreach($rs as $rk=>$rv):
			$params = array(
				'serial_id' => $rv['b_baby']
				);
			$friends[] = $this->baby_infov_md->get_one($params);
		endforeach;
		if(!empty($_POST['is_ajax'])):
			echo json_encode($friends);
		else:
			return $friends;
		endif;
	}

	public function getMsg($sid){
		$params = array(
			'baby_serial' => $sid
			);
		$msg = $this->msg_info_md->getData($params);

		foreach($msg as $mk=>$mv):
			$baby = $this->check_user(false,$mv['fbid']);
			$msg[$mk]['babyname'] = $baby['babyname'];
			$msg[$mk]['baby_serial'] = $baby['serial_id'];
			$msg[$mk]['photo'] = $baby['path'];
		endforeach;

		return $msg;
	}

	/**
	 * [add_friend 加好友]
	 */
	public function add_friend(){
		$fbid = $this->facebook->getUser();
		$check_user = $this->check_user(false,$fbid);
		$params[] = array(
			'a_baby' => $check_user['serial_id'],
			'b_baby' => $_POST['serial_id']
			);
		$params[] = array(
			'b_baby' => $check_user['serial_id'],
			'a_baby' => $_POST['serial_id']
			);
		$rs2 = $this->friend_info_md->getData($params[0]);
		if(sizeof($rs2)==0):
			$this->friend_info_md->insert_batch($params);
		endif;


		$sid = $check_user['serial_id'];
		if($_POST['is_view']):
			$sid = $_POST['serial_id'];
		endif;
		$friends = $this->getfriend($sid);

		$json = array(
			'success' => true,
			'friends' => $friends
			);
		echo json_encode($json);
	}
	
	public function result($babyname='') {
		$fbid = $this->facebook->getUser();
		$data = $this->_getBaseData();
		$data['fbid'] = $fbid;
		$user = $this->facebook_model->getUser($fbid);

		echo date('<br/>(1):i:s',time());
		$check_user = $this->check_user(false);
		echo date('<br/>(2):i:s',time());
		$into = false;
		if(sizeof($check_user)==0):
			$into = true;
			if($user['sex']=='male'):
				$params = array(
					'fbid_d' => $fbid,
					'daddy' => $user['name'],
					);
			else:
				$params = array(
					'fbid_m' => $fbid,
					'mom' => $usewr['name'],
					);
			endif;
			$params['babyname'] = $_POST['babyname'];
			$params['master'] = $fbid;
			$this->baby_info_md->insert($params);
			$serial_id = $this->db->insert_id();
			$this->check_friend($serial_id);
		endif;
		
		// var_dump($check_user);
		if($into):
			$check_user = $this->check_user(false);
		endif;
		$data['user'] = $check_user;
		echo date('<br/>(3):i:s',time());
		$msg = $this->getMsg($check_user['serial_id']);
		echo date('<br/>(4):i:s',time());
		$data['msg'] = $msg;

		echo date('<br/>(5):i:s',time());
		$friends = $this->getfriend($check_user['serial_id']);
		echo date('<br/>(6):i:s',time());

		$data['friends'] = $friends;

		$order = array(
			'friends_cnt' => 'desc'
			);
		$limit = 3;
		$offset = 0;
		echo date('<br/>(7):i:s',time());
		$rank = $this->rank($order,$limit,$offset);
		echo date('<br/>(8):i:s',time());
		$data['rank'] = $rank;
		$data['is_view'] = 'false';
		$data['is_friend'] = 'true';
		// var_dump($rank);

		$this->init_model->apply_template_with_ga($this->router->method . '_view', $data);
	}

	/**
	 * [view 檢視頁]
	 * @param  [type] $serial_id [description]
	 * @return [type]            [description]
	 */
	public function view($serial_id) {
		$data = $this->_getBaseData();

		$params = array(
			'serial_id' => $serial_id
			);
		$check_user = $this->baby_infov_md->get_one($params);

		$data['user'] = $check_user;

		$msg = $this->getMsg($check_user['serial_id']);

		$data['msg'] = $msg;

		$friends = $this->getfriend($check_user['serial_id']);

		$data['friends'] = $friends;

		$order = array(
			'friends_cnt' => 'desc'
			);
		$limit = 3;
		$offset = 0;
		$rank = $this->rank($order,$limit,$offset);
		$data['rank'] = $rank;

		$age = getAge($check_user['babybirthday']);
		$data['age'] = $age;
		$data['is_view'] = 'true';

		$fbid = $this->facebook->getUser();
		$mydata = $this->check_user(false,$fbid);
		$params = array(
			'a_baby' => $mydata['serial_id'],
			'b_baby' => $check_user['serial_id']
			);
		$rs = $this->friend_info_md->getCount($params);
		$is_friend = 'false';
		if($mydata['serial_id']==$check_user['serial_id'] || $rs==1):
			$is_friend = 'true';
		endif;
		$data['is_friend'] = $is_friend;

		$this->init_model->apply_template_with_ga('result_view', $data);
	}

	/**
	 * [set_pic 設定圖庫]
	 */
	public function set_pic(){
		$params = array(
			'path' => $_POST['path']
			);
		$where = array(
			'serial_id' => $_POST['serial_id']
			);
		$this->baby_info_md->update($params,$where);
		$json = array(
			'success' => true
			);
		echo json_encode($json);
	}

	/**
	 * [rank 排名]
	 * @param  array  $order [description]
	 * @return [type]        [description]
	 */
	public function rank($order=array(),$limit=0,$offset=0){
		$params = array();

		$list = $this->baby_infov_md->getData($params,$order,$limit,$offset);
		return $list;
	}

	public function unit(){
		// $this->load->library('unit_test');
		// $test = 1 + 1;

		// $expected_result = 2;

		// $test_name = 'Adds one plus one';

		// echo $this->unit->run('Foo', 'Foo');
		// echo date('Y-m-d H:i:s','2014-02-27T14:00:00+0000');
		$this->load->helpers('date');
		$post_date = '1079621429';
		$now = time();

		echo timespan($post_date, $now);
	}

	/**
	 * [joint 共同經營]
	 * @return [type] [description]
	 */
	public function joint(){
		$check_user = $this->check_user(false);
		// var_dump($check_user);
		if(empty($check_user['fbid_d'])):
			$params['fbid_d'] = $_POST['tofbid'];
		elseif(empty($check_user['fbid_m'])):
			$params['fbid_m'] = $_POST['tofbid'];
		endif;
		$params['is_joint'] = 'Y';
		$where = $this->get_where('baby_info');
		$success = FALSE;
		$this->baby_info_md->update($params,$where);
		$success = TRUE;
		$json = array(
				'success' => $success
		);
		
		echo json_encode($json);
	}

	/**
	 * [set_message 留言]
	 */
	public function set_message(){
		$fbid = $this->facebook->getUser();
		
		$params = $this->get_post('msg_info');
		$this->msg_info_md->insert($params);
		$msg = $this->getMsg($_POST['serial_id']);
		$json = array(
			'msg' => $msg
			);
		echo json_encode($json);
	}

	/**
	 * [edit 編輯寶寶]
	 * @return [type] [description]
	 */
	public function edit(){
		$params = $this->get_post('baby_info');
		$where = $this->get_where('baby_info');
		$success = FALSE;
		$this->baby_info_md->update($params,$where);
		$success = TRUE;
		$json = array(
				'success' => $success
		);
		
		echo json_encode($json);
	}

	public function ajax_list($o){
		$params = array();
		$s = '';
		if(!empty($_POST['search'])):
			$s = $_POST['search'];
			$params = array(
				'babyname' => $s
					);
		endif;
		
		$order = array();
		if($o==2):
			$order = array();
		elseif($o==3):
			$order = array(
				'friends_cnt' => 'desc'
				);
		endif;

		$list = $this->baby_infov_md->getLike($params,$order);
		$list = array_merge($list,$list,$list,$list,$list,$list,$list,$list,$list,$list);
		$json = array(
			'rank' => $list
			);
		echo json_encode($json);
	}

	public function get_post($table)
	{
		$data = null;
		switch ($table){
			case 'baby_info':
				$data['daddy'] = $this->input->post('daddy');
				$data['mom'] = $this->input->post('mom');
				$data['babybirthday'] = $this->input->post('babybirthday');
				$data['sex'] = $this->input->post('sex');
				$data['is_update'] = 'Y';
				break;
			case 'article_info':
				$data['post_id'] = $this->input->post('post_id');
				$data['title'] = $this->input->post('title');
				$data['start_time'] = $this->input->post('start_time');
				$data['end_time'] = $this->input->post('end_time');
				break;
			case 'msg_info':
				$data['fbid'] = $this->facebook->getUser();
				$data['message'] = $this->input->post('message');
				$data['baby_serial'] = $this->input->post('serial_id');
		};

		return $data;
	}

	public function get_where($table){
		$data = null;
		switch ($table){
			case 'baby_info':
				$data['serial_id'] = $this->input->post('serial_id');
				break;
			case 'article_info':
				$data['post_id'] = $this->input->post('post_id');
				break;
		};

		return $data;
	}
	
	public function redirect($fbid=0){
		if($fbid==0):
			echo '<script>window.open("'.APP_HOST.'","_top");</script>';
		else:
			echo '<script>window.open("'.APP_HOST.'index.php/main/message/'.$fbid.'","_top");</script>';
		endif;
		exit;
	}
	
	public function setData(){
		$table = 'baby_info';
		$fbid = $this->facebook->getUser();
		
		$params = array(
				'fbid' => $fbid
				);
		$rs = $this->db_md->getCount($table,$params);
		$params = array(
				'username' => str_replace('%0D','',$_POST['username']),
				'email' => str_replace('%0D','',$_POST['email']),
				'tel' => str_replace('%0D','',$_POST['tel'])
				);
		$user = $this->facebook_model->getUser($fbid);
		$params['fbid'] = $fbid;
		$params['fbname'] = $user['name'];
		$params['is_join'] = 'Y';
		$where = array(
				'fbid' => $fbid
				);
		$this->db->update($table,$params,$where);
		$json = array(
				'success' => 1
				);
		echo json_encode($json);
	}

	public function po_wall() {
		$fb_title = $this->APPTITLE();
		$fbid = $this->facebook->getUser();
		
		$fuser = $_SESSION[$fbid.'fuser'];
		
		$table = 'baby_info';
		$params = array(
				'fbid' => $fbid,
				'is_join' => 'Y'
		);
		$rs = $this->db_md->getCount($table,$params);
		if($rs==0){
			$params['is_join'] = 'N';
			$this->db->insert($table,$params);
			foreach($fuser as $fk=>$fv):
				$table = 'tag_record';
				$params = array(
						'fbid' => $fbid,
						'tofbid' => $fv['uid']
						);
				$this->db->insert($table,$params);
			endforeach;
		}
		
		$user = $this->facebook_model->getUser($fbid);
		$file = WEB_HOST.MERGE_PATH.$fbid.'_wall.jpg';
		$file = WEB_HOST.'images/wall-2.jpg';
		$file = str_replace('https','http',$file);
// 		$this->load->library('bitly');
		$url = site_url('main/redirect').'/'.$fbid;
// 		$bitly = '';
// 		$bitly = $this->bitly->shorten($url);
// 		$bitly = $bitly->$url->shortUrl;
		
		$message = '在家靠父母，出外靠朋友，是誰在你人生中的重要時刻為你撐腰，成為你的最佳戰友？

____是我的超完美強棒應援團，朋友們快來留言，讓我們一起擊出一支無人能擋的HOME RUN吧>>>'.$url;

		$params = array(
				'pic' => $file,
				'album_name' => $fb_title.' photos',
				'album_description' => $fb_title.' photos',
				'picture_description' => $message,
		);
		$pid = $this->facebook_model->album($params);
// 		foreach($fuser as $fk=>$fv):
// 			$params = array(
// 					'upload_photo_id' => $pid['id'],
// 					'x' => 5,
// 					'y' => 5,
// 					'uid' => $fv['uid']
// 			);
// 			$this->facebook_model->tag($params);
// 		endforeach;
		$json = array(
				'success' => true
		);
		echo json_encode($json);
	}

	public function ajaxrecord(){
		$table = $_POST['table'];
		$fbid = $this->facebook->getUser();
		$params = array(
				'fbid' => $fbid
		);
		$success = $this->db->insert($table,$params);
		$json = array(
				'success' => $success
		);
		echo json_encode($json);
	}
	
	private function preload_images(){
		foreach (glob(IMAGE_PATH."/*") as $f) $images[]=  "'$f'";
		return implode(',',$images);
	}

	public function add_tab()
	{
		$url = "http://www.facebook.com/dialog/pagetab?app_id=" . FBAPP_ID . "&next=" . APP_HOST;
		echo "<a href='$url'>$url</a>";
		exit;
	}

	private function getFansInfo() {
		$rows['page_id'] = fans_page_id;
		$rows['page_url'] = fans_page;

		return $rows;
	}

	public function ajaxtouch(){
	}

	private function APPTITLE(){
		$fbapp_title = $this->facebook_model->getAPPTitle();
		return $fbapp_title[0]['display_name'];
	}
}

?>
