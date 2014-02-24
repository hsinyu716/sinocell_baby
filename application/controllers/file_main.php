<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class File_main extends CI_Controller {

	public function index(){
	}

	public function do_upload(){
		
		$error = "";
		$msg = "";
		$msg2 = "";
		$fileElementName = 'fileToUpload';
		$size=@filesize($_FILES[$fileElementName]['tmp_name']);
		if($size>5242880){
			$error ="圖檔大小超過5MB喔!!";
		}else  {
			 $uptypes = array (
				'image/jpg',
				'image/jpeg',
				'image/pjpeg',
				'image/gif',
				'image/png'
			  );
// 			 if(!in_array($_FILES[$fileElementName]['type'], $uptypes)) {
// 				$error ='上傳圖片類型必須為jpg,png,gif喔!!';
// 			 }
		}
		
		if($error==""){
			$scale = true;
			$filename=$_FILES[$fileElementName]['tmp_name'];
			if($_POST['imgsize']=='wall'){
				$max_width = 540;
				$max_height = 480;
				$scale = false;
			}else if($_POST['imgsize']=='webimg'){
				$max_width = 540;
				$max_height = 480;
				$scale = false;
			}else if($_POST['imgsize']=='webimgth'){
				$max_width = 180;
				$max_height = 160;
			}else if($_POST['imgsize']=='thumb'){
				$max_width = 180;
				$max_height = 160;
			}else if($_POST['imgsize']=='picthumb'){
				$max_width = 180;
				$max_height = 180;
			}
			
			$dir = 'tmp/'.$_POST['dir'];
			if (!is_dir($dir)) {
				mkdir($dir);
			}
			
			$params = array(
				'filename' => $filename,
				'max_width' => $max_width,
				'max_height' => $max_height,
				'scale' => $scale,
				'savename'=>$dir.'/'.time()
				);
			$this->load->library('gd_creater');
			$json = $this->gd_creater->upload($params);
			$json['error'] = $error;
		}
// 		@unlink($_FILES['file']);
		echo json_encode($json);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */