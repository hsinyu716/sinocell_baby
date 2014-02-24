<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of bp_log_model
 *
 * @author LowkeyMan
 */
class Init_model extends CI_Model{
    
    function __construct() {
        parent::__construct();
        header('p3p: CP="ALL DSP COR PSAa PSDa OUR NOR ONL UNI COM NAV"');
        $this->load->library('Template');
//         $no_auth_page = array('index','tab','redirect','ajaxtouch');
//         if(!in_array($this->router->method,$no_auth_page))
//             $this->go_auth();
    }
    
    function apply_template($view='index',$data=array(),$title=''){
        //$this->template->write_view('menu', 'menu'); //top menu, repeat it.
        $this->template->write_view('content', $view,$data); //load views/file
        $this->template->write('title',$title); //title, option.
        $this->template->add_js('js/jquery.bpopup-0.9.3.min.js');
        $this->template->add_js('js/jquery.blockUI-2.59.0.js');
        $this->template->add_js('js/myjs.js');
        $this->template->add_js('js/dw.js');
        $this->template->add_css('style/css.css');
        $this->template->add_css('style/tab.css');      
        $this->template->add_css('style/fb-buttons.css');
        $this->template->render();/**/
    }

    function apply_template_with_ga($view='index',$data=array(),$title=''){
        $this->template->add_js('js/google-analytics.js');
        $this->apply_template($view,$data,$title);
    }    
    
    //授權
    function go_auth(){
        $fb_data = $_SESSION['fb_data'];
        if((!$fb_data['uid']) or (!$fb_data['me'])){
            $loginUrl = $fb_data['loginUrl'];
            echo "<script> window.open('$loginUrl','_top'); </script>";
            exit;
        }
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
}

?>
