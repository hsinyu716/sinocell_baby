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
			$max_width = 170;
			$max_height = 170;
			$scale = false;
			
			$dir = 'tmp/'.$_POST['serial_id'];
			if (!is_dir($dir)) {
				mkdir($dir);
			}
			
			$file_id = time();
			$params = array(
				'filename' => $filename,
				'max_width' => $max_width,
				'max_height' => $max_height,
				'scale' => $scale,
				'savename'=>$dir.'/'.$file_id
				);

			$this->load->library('gd_creater');
			$json = $this->gd_creater->upload($params);
			$json['error'] = $error;

			$params = array(
				'file_id' => $file_id,
				'path' => $json['src'],
				'baby_serial' => $_POST['serial_id']
				);
			$this->file_info_md->insert($params);
			$params = array(
				'path' => $json['src']
				);
			$where = array(
				'serial_id' => $_POST['serial_id']
				);
			$this->baby_info_md->update($params,$where);
		}
// 		@unlink($_FILES['file']);
		echo json_encode($json);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */