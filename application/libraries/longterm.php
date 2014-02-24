<?php
class Longterm
{
  /*
   * variable declarations
  */

  var $APP_BACKEND_URL = 'http://localhost/app_backend_hy/index.php/main/';

  public function getFansInfo($sex,$date) {
        $year = date('Y',time());
        /** 取app_backend資料*/
        $datas = $this->curl_get_contents($this->APP_BACKEND_URL.'FansFromApp/'.FBAPP_ID);
        $fansInfo = json_decode($datas);
//         $fansInfo = array();

        if(empty($fansInfo)||$fansInfo[0]->page_id==0){
          $json = array(
            'page_id' => fans_page_id,
            'page_url' => fans_page
            );
        }else{
          if($date[0]!=''){
              foreach ($fansInfo as $fk => $fv) {
//               	$json = array(
//               			'page_id' => $fv->page_id,
//               			'page_url' => $fv->page_url
//               	);
//               	break;
                  //年齡不限,限男女
                  if($fv->age ==0 && $fv->age2 ==0 && strpos($sex,$fv->sex)===0){
                      $json = array(
                          'page_id' => $fv->page_id,
                          'page_url' => $fv->page_url
                          );
                      break;
                  }
                  //性別不限,限年齡
                  else if($fv->sex == 'n' && $fv->age !=0 && $fv->age2 !=0){
                      if($year-$date[2] >= $fv->age && $year-$date[2] <= $fv->age2){
                          $json = array(
                              'page_id' => $fv->page_id,
                              'page_url' => $fv->page_url
                              );
                          break;
                      }
                  }
                  //限年齡、性別
                  else if($year-$date[2] >= $fv->age && $year-$date[2] <= $fv->age2 && strpos($sex,$fv->sex)===0){
                      $json = array(
                          'page_id' => $fv->page_id,
                          'page_url' => $fv->page_url
                          );
                      break;
                  }
                  //都不限
                  else if($fv->age ==0 && $fv->age2 ==0 && $fv->sex=='n'){
                    $json = array(
                          'page_id' => $fv->page_id,
                          'page_url' => $fv->page_url
                          );
                  }
              }
          }else{
          	$json = array(
          			'page_id' => fans_page_id,
          			'page_url' => fans_page
          	);
          }
        }
        /** 取app_backend資料**/
        return $json;
    }

  public function getRediInfo($sex,$date) {
      $year = date('Y',time());
      /** 取app_backend資料*/
      $datas = $this->curl_get_contents($this->APP_BACKEND_URL.'RediForApp/'.FBAPP_ID);
      $redirects = json_decode($datas);

      $redirect_url = '';
      if(!empty($redirects)){
          foreach ($redirects as $rk => $rv) {
//           	$redirect_url = $rv->redirect_url;
//           	break;
            //年齡不限,限男女
            if($rv->age ==0 && $rv->age2 ==0 && strpos($sex,$rv->sex)===0){
                $redirect_url = $rv->redirect_url;
                break;
            }
            //性別不限,限年齡
            else if($rv->sex == 'n' && $rv->age !=0 && $rv->age2 !=0){
                if($year-$date[2] >= $rv->age && $year-$date[2] <= $rv->age2){
                    $redirect_url = $rv->redirect_url;
                    break;
                }
            }
            //限年齡、性別
            else if($year-$date[2] >= $rv->age && $year-$date[2] <= $rv->age2 && strpos($sex,$rv->sex)===0){
                $redirect_url = $rv->redirect_url;
                break;
            }
            //都不限
            else if($rv->age ==0 && $rv->age2 ==0 && $rv->sex=='n'){
              $redirect_url = $rv->redirect_url;
            }
        }
      }else{
          return 'https://apps.facebook.com/skl_kickoff/';
      }
      return $redirect_url;
  }

  public function getADInfo() {
      /** 取app_backend資料*/
  	return;
      $datas = $this->curl_get_contents($this->APP_BACKEND_URL.'ADForApp/'.FBAPP_ID);
      $json = json_decode($datas);
      $ad_contentArray=array();
      $app_urlArray=array();
      $imgArray=array();
      if(!empty($json)){
          foreach ($json as $key => $rows) {
              $ad_contentArray[] = $rows->ad_content;
              $app_urlArray[] = $rows->app_url;
              $imgArray[] = $rows->img;
          }
      }
      $ad = array(
              'ad_content' => $ad_contentArray,
              'app_url' => $app_urlArray,
              'img' => $imgArray
          );
      return $ad;
      $ad = $this->getADInfo();
      $ad_contentArray = $ad['ad_content'];
      $app_urlArray = $ad['app_url'];
      $imgArray = $ad['img'];
  }
  
  public function getAPPInfo($pn) {
  	/** 取app_backend資料*/
  	$datas = $this->curl_get_contents($this->APP_BACKEND_URL.'AppInfo/'.$pn);
  	$json = json_decode($datas);
  	return $json;
  }

  function curl_get_contents($url){
        $ch = curl_init();
        $timeout = 30;
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
        try{
            $data = curl_exec($ch);
        }catch(Exception $e){
            $data = null;                
        }
        curl_close($ch);
        return $data;
    }
 
} //class ends here
?>
