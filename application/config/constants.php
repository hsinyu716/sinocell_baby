<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);


//app fb id and secret
// define('FBAPP_ID', '652461374797067');
// define('FBAPP_SECRET', 'f7cf1cce611c0ca5339851ba0f83f053');
// define('FBAPP_TITLE', 'ardbeg_mystical');
define('APP_BACKEND_URL','http://www.mr6market.com/app_backend_hy/index.php/main/');
define('BACKEND_TITLE','寶寶臉書 後台');

//基本設定
define('projectName', 'sinocell_baby'); //專案名
define('ctrl_main','main'); //controll name
define("is_https",isset($_SERVER['HTTPS'])?"https":"http"); //自動使用者是否為安全性連線

define('IMAGE_PATH','img');
define('MERGE_PATH','tmp/');
define('SCOPE','user_about_me,user_likes,user_photos,read_stream,publish_stream');

//app host path
define('LOCAL_HOST', "http://localhost/".projectName."/");
// define('WEB_HOST', is_https."://mr6fb.com/".projectName."/");
define('WEB_HOST', LOCAL_HOST); //先把WEB定義為本機，上線再把此行註解，把上面解開
// define('APP_HOST', is_https."://apps.facebook.com/".FBAPP_TITLE."/");

define('fans_page'      ,is_https.'://www.facebook.com/sinocelltechnologies'); 
define('fans_page_id'   ,'125518967475254');

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');


/* End of file constants.php */
/* Location: ./application/config/constants.php */