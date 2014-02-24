<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

header('Content-type: text/html;charset=utf-8'); 
 
class Gd_creater {

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

    private function mergeText($image_p, $font_type = 'images/msjhbd.ttf',$font_size,$font_color,$text,$x,$y,$isp=0,$x1,$y1){

        $text_box = imagettfbbox($font_size,0,$font_type,$text);
        $text_width = $text_box[2]-$text_box[0];
        $text_height = $text_box[3]-$text_box[1];

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
            $y = $y + ($y1-$text_height)/2;
        }else if($isp==6){ //計算x、x1  y、y1置中
            $x = $x + ($x1-$text_width)/2;
            $y = $y + ($y1-$text_height)/2;
        }else if($isp==7){//計算從x1-字寬
            $x = $x1- $text_width;
        }
        
        ImageTTFText($image_p, $font_size, 0, $x, $y, $font_color, $font_type, $text); 
        return $image_p;
    }

    function makeP($file_f='', $size_w=172,$size_h=172,$color){ 
        
        $gs = getimagesize($file_f);
        if ($gs[2] == 1)     { $img_pic_f = imagecreatefromgif($file_f);  }
        else if ($gs[2] == 2){ $img_pic_f = imagecreatefromjpeg($file_f); }
        else if ($gs[2] == 3){ $img_pic_f = imagecreatefrompng($file_f);  } 

        if( $gs[0]>$gs[1] ){
            $w = $size_w;
            $h = $gs[1]*$w/$gs[0];
            $x = 0;
            $y = ($size_h-$h)/2;
        }else{
            $h = $size_h;
            $w = $gs[0]*$h/$gs[1];
            $y = 0;
            $x = ($size_w-$w)/2;            
        }
                
        $new_width = $size_w;
        $new_height = $size_h;
        $image_p = imagecreatetruecolor($new_width, $new_height) or die("Cannot Initialize new GD image stream");
        $white = $color;
        imagefill($image_p, 0, 0, $white);
        
        imagecopyresampled($image_p, $img_pic_f, $x, $y, 0, 0, $w, $h, $gs[0], $gs[1]);
        imagejpeg($image_p, $file_f, 100);      
        imagedestroy($image_p);
        imagedestroy($img_pic_f);       
    }
        
    function merge($data){
        $filename = $data['bg'];
        // 產生底圖
        $image_p = $this->original($filename,$data['bw'],$data['bh']);

        foreach ($data['img_array'] as $key => $value) {
            $filename = $value['filename'];
            $gs = getimagesize($filename);
            $width = $gs[0];
            $height = $gs[1];
            $type = $gs[2];
            if($value['resize']==true){
                // 縮圖留邊
                list($r,$g,$b) = explode(',', $value['makep_color']);
                $this->makeP($filename, $value['imgw']+$value['borderw']*2,$value['imgh']+$value['borderw']*2,imagecolorallocate($image_p, $r,$g,$b));
                $image2 = $this->original($filename,$width,$height);
                $image = $image2;
                // 加border
                if($value['border']==1){
                    $border = imagecreatetruecolor($value['imgw']+$value['borderw']*2, $value['imgh']+$value['borderw']*2) or die("Cannot Initialize new GD image stream");
                    $white = imagecolorallocate($border, 255,0,0);
                    imagefill($border, 0, 0, $white);

                    $image = $border;
                    imagecopyresampled($image, $image2, $value['borderw'],$value['borderw'], 0, 0, $value['imgw'], $value['imgh'], $value['imgw'], $value['imgh']);
                }
            }else{
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
            }
            imagecopyresampled($image_p, $image, $value['imgx'], $value['imgy'], 0, 0, $value['imgw'], $value['imgh'], $width, $height);
            if(!isset($value['del']))
                unlink($filename);
        }
        
        foreach ($data['text_array'] as $key => $value) {
            $font_type = $value['font_type'];
            $font_size = $value['fontsize'];
            list($r,$g,$b) = explode(',', $value['fontcolor']);
            $font_color = imagecolorallocate($image_p, $r,$g,$b);

            $text = $value['text'];

            if($value['br']=='Y'){
                $wish_word = '';
                $wish_words = $text;

                $len = $value['brcnt'];
                $tmp = '';
                if(mb_strlen($wish_words) > $len){
                    for($i=0;$i<floor(mb_strlen($wish_words)/$len);$i++){
                        $wish_word .= mb_substr($wish_words,($i*$len),$len)."\n";
                        if((floor(mb_strlen($wish_words)/$len)*$len)<=mb_strlen($wish_words)){
                            $tmp = mb_substr($wish_words,(floor(mb_strlen($wish_words)/$len)*$len),mb_strlen($wish_words));
                        }
                    }
                }else{
                    $wish_word = $wish_words;
                }
                $wish_word .= $tmp;
                $text = $wish_word;
            }

            $image_p = $this->mergeText($image_p,$font_type,$font_size,$font_color,$text,$value['tx'],$value['ty'],$value['isp'],$value['tx1'],$value['ty1']);
        }

        imagejpeg($image_p, $data['output'],100);

        imagedestroy($image_p);
    }

    public function upload($params){
        $max_width=$params['max_width'];
        $max_height=$params['max_height'];
        $url=$params['savename'];
        $cut=false;
        $filename = $params['filename'];
        list($width, $height, $image_type) = getimagesize($filename);
        
        switch ($image_type)
        {
            case 1: 
                $src = imagecreatefromgif($filename);
                $msg= $url.'.gif'; 
                $msg2= $url.'_o.gif'; break;
            case 2: 
                $src = imagecreatefromjpeg($filename);
                $msg= $url.'.jpg'; 
                $msg2= $url.'_o.jpg';   break;
            case 3: 
                $src = imagecreatefrompng($filename);
                $msg= $url.'.png'; 
                $msg2= $url.'_o.png';  break;
            default: $msg='' ;return '';  break;
        }

        copy($filename,$msg);
        copy($filename,$msg2);

        $image_p = $this->original($msg,$width,$height);
        
        $this->makeP($msg, $max_width,$max_height,imagecolorallocate($image_p, 255,255,255));
        
        chmod($msg, 0777);

        $json = array(
                'src' => $msg,
                'src_o' => $msg2
            );
        return $json;
    }
    
    public function delimg(){
        foreach(glob(MERGE_PATH."/*") as $entry) {
            if (in_array($entry, array(".", "..")) === false) {
                if(!is_dir($entry) && filectime($entry)<strtotime("-30 minutes")){
                    unlink($entry);
                }
            }
        } 
    }
}
 
/* End of file gd_creater.php */
 