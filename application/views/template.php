<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html  xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml" xmlns:og="https://opengraph.org/schema/">
    <head>
        <title><?=$fb_title;?></title>
        <base href="<?= WEB_HOST; ?>" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<script src="<?=is_https?>://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
		<script src="<?=is_https?>://ajax.googleapis.com/ajax/libs/jqueryui/1.9.0/jquery-ui.min.js"></script>
		<link rel="stylesheet" type="text/css" href="<?= WEB_HOST ?>css/jquery-ui-1.8.20.custom.css" />

        <script type="text/javascript" src="<?= WEB_HOST ?>js/jquery.imgpreload.min.js" ></script>
        <?= $_scripts ?>
        <?= $_styles ?>
        
        <script type="text/javascript" src="<?= WEB_HOST ?>js/bootbox.min.js"></script>
        <script type="text/javascript" src="<?= WEB_HOST ?>bootstrap/js/bootstrap.min.js" ></script>
        <link rel="stylesheet" href="<?= WEB_HOST ?>bootstrap/css/bootstrap.min.css">
        <script src="<?= WEB_HOST ?>js/angular.min.js"></script>
        <script type="text/javascript" src="<?= WEB_HOST ?>js/controller.js" ></script>
        
        <script type="text/javascript" src="<?= WEB_HOST ?>js/toastr/toastr.js" ></script>
        <link href="<?= WEB_HOST ?>js/toastr/toastr.css" rel="stylesheet" type="text/css" />

        <!--[if lt IE 9]>
        <script src="js/dist/html5shiv.js"></script>
        <![endif]-->
        <script type="text/javascript">
	        $(function(){
	        	fbinit('<?=FBAPP_ID ?>');
	            timer = setInterval(touch,60000);
	            preload_images([<?=$images?>]);
	        });

            function record(t){
                $.ajax({
                    type:"POST", 
                    url:"<?= site_url('main/ajaxrecord') ?>", 
                    data:{
                        'table':t
                    },
                    success:function(res){
                    }
                });
            }

            var isfans = 0;

            function is_fan() {
                if(isfans==1){
                    next_();return;
                }
                FB.api(
                {
                    method: 'pages.isFan',
                    page_id: '<?=$page_id;?>' 
                },
                function(response) {
                    if(response) {
                        next_();
                    } else { 
                        _show($('#isfans'));
                        //setTimeout("is_fan()",5000);  
                    }
                });
            }

            var func = 'result';
            function next_(){
            	_show($('#loading'));
                if(func=='result'){
	                location.href="<?=site_url('main/result');?>/"+$('#babyname').val();
                }else if(func=='check'){
                	check_user(fbid);
                }
            }

            function check_user(o){
            	_show($('#loading'));
				$.ajax({
                    type:"POST", 
                    url:"<?= site_url('main/check_user') ?>", 
                    data:{
                    	'fbid' : o
                    },
                    before:function(){

                    },
                    success:function(res){
                    	if(res){
                    		location.href="<?=site_url('main/result');?>";
                    	}else{
	            			_show($('#loading'));
                    		show_toastr('toast-top-right','error','您尚未製作過！','');
                    	}
                    }
                });
			}
            
            function check_FB(o){
            	func = o;
                if(typeof FB != 'undefined'){
                    setTimeout("fb_login('<?=SCOPE;?>')",2000);
                }else{
                    setTimeout("check_FB('"+o+"')",2000);
                }
            }

            function touch(){
                $.ajax({
                    type:"POST", 
                    url:"<?= site_url('main/ajaxtouch') ?>", 
                    data:{
                    },
                    success:function(html){
                    }
                });
            }

            function pop_msg(msg){
				$('#msg').html(msg);
                _show($('#div_msg'));			
			}
    
			function close_msg(){
				_show($('#div_msg'));			
			}
        </script>
        
        <style>
        .bootbox{
	        z-index:9999;
	    }
        </style>
    </head>
    <body ng-app="myApp">    
    
        <div id="fb-root"></div>
        <div id="loading" class="radius"><img src='images/loading.gif' />&nbsp;處理中，請稍等!</div>
        <div id="isfans" style='font-family:微軟正黑體;text-align:center;display:none;background-color:white;width:300px;height:125px;position:relative;top:20px;'>
         	請按讚加入粉絲團！
            <div id="like" style="border: 0px solid red; position: absolute;left:0px;top:50px;">
                <div class="fb-like-box" data-href="<?= $page_url ?>" data-width="300" data-height="150" data-show-faces="false" data-stream="false" data-header="false"></div>
            </div>  
            <div style="margin-top: 100px;">
            	<!-- <div onclick="javascript:$('#isfans').bPopup().close();isfans=1;next_();" style="font-size:12px;text-align:center;position: absolute; right: 0px; top: 0px; width:12px; height: 12px; background: #29447e; color: white; cursor: pointer;">X</div> -->
            </div>
        </div>
        
        <div id="content">
            <?php print $content ?>
        </div>
        <div style="clear:both;text-align:center;height:30px;"><a target="_blank" style="color:#aaa; font-size: 10px; text-decoration: none; margin-buttom:50px;" href="<?=WEB_HOST?>privacy_policy.html">Privacy Policy</a></div>
    </body>
</html>