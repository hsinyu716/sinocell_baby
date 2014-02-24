
<script>
$(function(){
	_show($('#loading'));
	func = 'message';
	check_FB();
});

var o_fbid = '<?= $fbid;?>';
var msgurl = "<?= site_url('main/setMsg') ?>";
var indexurl = "<?=site_url('main/index');?>";

function check_msg(){
	$.ajax({
        url: "<?= site_url('main/check_msg') ?>",
        cache: false,
        type: 'post',
        data:{
				'fbid':o_fbid,
				'tofbid':fbid
            },
        dataType:'json',
        beforeSend: function(html){
        },
        error: function(e){
            //alert("error:"+e.responseText);
        },
        success: function(res){
        	if(res.cnt==1){ //上線時改1
        		_show($('#loading'));
        	}else{
            	location.href=indexurl;
        	}
        },
        complete:function(){
            
        }
     });
}
</script>

<div ng-app="myApp" ng-controller="msgController">
	<textarea id="message" cols="10" rows="5" placeholder="請輸入留言"></textarea>
	<button ng-click="submit_();">留言</button>
</div>