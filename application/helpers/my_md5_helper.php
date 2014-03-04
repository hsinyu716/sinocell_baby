<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * Author : Lensic
 * Blog   : http://lensic.sinaapp.com/
 */
 
/*
 * 自定义 md5 加密算法
 * 
 * $str : 需加密的字符串
 */
function str_md5($str)
{
	$CI = &get_instance();
	return md5(base64_encode($CI->config->item('web_encryption_key_begin')) . md5($str) . base64_encode($CI->config->item('web_encryption_key_end')));
}

/* End of file my_md5_helper.php */
/* Location: ./application/helpers/my_md5_helper.php */