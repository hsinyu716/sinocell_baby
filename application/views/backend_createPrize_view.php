<script type="text/javascript" src="js/jquery.upload-1.0.2.min.js"></script>

<script>
var prizes = '';
var point = 0;

$(function(){
	$.each($('.upload_div'),function(i,v){
		var obj = $(this);
		obj.children().children().eq(0).change(function() {
	        var ext = obj.children().children().val().split('.').pop().toLowerCase();
	        if($.inArray(ext, ['jpg','jpeg','png']) == -1) {
	            alert('請上傳jpg,png檔！');
	        }else{
	        	_show($('#loading'));
	        	obj.children().upload('<?=site_url('file_main/do_upload');?>', function(resp) {
	        		_show($('#loading'));
	                if(resp.error!=""){
	                    alert( resp.error );                                                            
	                    return false;
	                }else{
	                	$('#img').val(resp.src);
	                	$('#img').attr('disabled',false);
	                }                               
	            }, 'json');
	        }
	    });
	});
});

var saveUrl = '<?=site_url('backend/save');?>';
var prizeUrl = '<?=site_url('backend/prize');?>';
</script>

<div ng-controller="prizeController">

<form id="form" name="form" class="css-form" novalidate style="width:400px;">
	<input type="hidden" name="table" value="prize_info"/>
	<div class="input-group">
		<span class="input-group-addon">獎品標題：</span>
		<input type="text" id="title" name="title" value="" alt="請輸入獎品標題" class="required form-control"/>
	</div>
	<br/>
	<div class="input-group">
		<span class="input-group-addon">獎品圖片：</span>
		<input type="text" id="img" name="img" value="" disabled alt="請上傳獎品圖片" class="required form-control"/>
	</div>
	<div class="upload_div">
		<div id="fileToUploadDivweb">
        	<input name="fileToUpload" id="fileToUploadweb" type="file" size="20" />
        	<input name="imgsize" type="hidden" value="webimg"/>
        	<input name="dir" type="hidden" value="prize"/>
        </div>
    </div>
	<br/>
	<div class="input-group">
		<span class="input-group-addon">兌換點數：</span>
		<input type="text" id="point" ng-model="point" name="point" numbers-only="numbers-only"  class="required form-control"/>
	</div>
</form>
<br/>
<button type="button" class="btn btn-default" ng-click="submit_();"><span class="glyphicon glyphicon-save"></span>送出</button>
<button type="button" class="btn btn-default" ng-click="back_();"><span class="glyphicon glyphicon-remove"></span>取消</button>
</div>

