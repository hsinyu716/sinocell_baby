<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author Hsinyu
 */
class Gd_md extends CI_Model{

	var $sizelimit_x = 180;
	//the limit of the image width
	var $sizelimit_y = 180;
	//the limit of the image height

    

	private function drawboldtext($image, $size, $angle, $x_cord, $y_cord, $color, $fontfile, $text) 
	{ 
	   $_x = array(0.2, 0, 0.2, 0, -0.2, -0.2, 0.2, 0, -0.2); 
	   $_y = array(0, -0.2, -0.2, 0, 0, -0.2, 0.2, 0.2, 0.2); 
	   for($n=0;$n<=8;$n++) 
	   { 
	      ImageTTFText($image, $size, $angle, $x_cord+$_x[$n], $y_cord+$_y[$n], $color, $fontfile, $text); 
	   } 
	} 

	private function getXY($text,$im,$font_size,$angle,$font_type){
	    $p = array();
	    
	    $image_width = imagesx($im);  
	    $image_height = imagesy($im);
	    $text_box = imagettfbbox($font_size,$angle,$font_type,$text);

	    $text_width = $text_box[2]-$text_box[0]; // lower right corner - lower left corner
	    $text_height = $text_box[3]-$text_box[1];
	    $p[] = $text_width;
	    $p[] = $text_height;
	    $p[] = ($image_width/2) - ($text_width/2);
	    $p[] = ($image_height/2) - ($text_height/2);

	    return $p;
	}

	private function original($filename,$width,$height){
		$new_width = $width;
        $new_height = $height;
        $image_p = imagecreatetruecolor($new_width, $new_height) or die("Cannot Initialize new GD image stream");
        $white = imagecolorallocate($image_p, 255, 255, 255);
		imagefill($image_p, 0, 0, $white);

	    list($img_width, $img_height, $type, $attr) = getimagesize($filename);
	    switch ($type) {
			case 1:
				$image = @imagecreatefromgif($filename);
				break;
			case 2:
				$image = @imagecreatefromjpeg($filename);
				break;
			case  3:
				$image = @imagecreatefrompng($filename);
	 		break;
	    }

		imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $img_width, $img_height);
		imagedestroy($image);
		return $image_p;
	}

	private function mergeText($image_p, $font_type = 'font/msjh2bd.ttf',$font_size,$font_color,$text,$x,$y,$isp,$x1,$y1){

		$text_box = imagettfbbox($font_size,0,$font_type,$text);
	    $text_width = $text_box[2]-$text_box[0];
	    $text_height = $text_box[3]-$text_box[1];

	    // if($text_width>($x1-$x)){
	    // 	$font_size = 
	    // }

		$p = $this->getXY($text,$image_p,$font_size,0,$font_type);
		if($isp==1){ //xy皆置中
			$x = $p[2];
			$y = $p[3];
		}else if($isp==2){ //x置中
			$x = $p[2];
		}else if($isp==3){ //y置中
			$y = $p[3];
		}else if($isp==4){ //計算x、x1置中
			$x = $x + ($x1-$text_width)/2;
		}else if($isp==5){ //計算y、y1置中
			$y = $y + (($y1-$y)-$text_height)/2;
		}else if($isp==6){ //計算x、x1  y、y1置中
			$x = $x + (($x1-$x)-$text_width)/2;
			$y = $y + (($y1-$y)-$text_height)/2;
		}else if($isp==7){//計算從x1-字寬
			$x = $x1- $text_width;
		}
		ImageTTFText($image_p, $font_size, 0, $x, $y, $font_color, $font_type, $text); 
		return $image_p;
	}

	private function merge_func($image_p,$image2,$image_px=0, $image_py=0, $image2_x=0, $image2_y=0,$width,$height){
		imagecopyresampled($image_p, $image2, $image_px, $image_py, $image2_x, $image2_y, $width, $height, $width, $height);
		imagedestroy($image2);
		return $image_p;
	}

	function makeP($file_f='', $size=172,$color){	
		
		$gs = getimagesize($file_f);
		if ($gs[2] == 1)     { $img_pic_f = imagecreatefromgif($file_f);  }
		else if ($gs[2] == 2){ $img_pic_f = imagecreatefromjpeg($file_f); }
		else if ($gs[2] == 3){ $img_pic_f = imagecreatefrompng($file_f);  }	

		if( $gs[0]>$gs[1] ){
			$w = $size;
			$h = $gs[1]*$w/$gs[0];
			$x = 0;
			$y = ($size-$h)/2;
		}else{
			$h = $size;
			$w = $gs[0]*$h/$gs[1];
			$y = 0;
			$x = ($size-$w)/2;			
		}
				
		// $file_white = "images/white".$size."x".$size.".png";
		// $img_white = imagecreatefrompng($file_white);
		$new_width = $size;
        $new_height = $size;
        $image_p = imagecreatetruecolor($new_width, $new_height) or die("Cannot Initialize new GD image stream");
        $white = $color;
		imagefill($image_p, 0, 0, $white);
		
		imagecopyresampled($image_p, $img_pic_f, $x, $y, 0, 0, $w, $h, $gs[0], $gs[1]);
		imagejpeg($image_p, $file_f, 100);		
		imagedestroy($image_p);
		imagedestroy($img_pic_f);		
	}

	function merge($fbid){
		// $me = $this->facebook->api('/me');
		$me_fb_id = $fbid;
		// $name = $me['name'];

		$filename = 'images/wall.jpg';
		// 產生底圖
		$image_p = $this->original($filename,403,440); //調整大小

		// 合字
		$font_type = 'images/msjhbd.ttf';
		
		$font_size = 20;
		$font_color = imagecolorallocate($image_p, 255, 255, 255);
		$user = $this->facebook_model->getUser($me_fb_id);
		$text = $user[0]['name'];

		$image_p = $this->mergeText($image_p,$font_type,$font_size,$font_color,$text,$x=155,$y=125,$isp=0,$x1=310,$y1=250);

		// 

		imagejpeg($image_p, 'tmp/'.$me_fb_id.'.jpg',100);

		imagedestroy($image_p);
	}
}

?>