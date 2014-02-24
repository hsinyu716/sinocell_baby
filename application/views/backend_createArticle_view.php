
<script>
var articles = '';
var article = null;

$(function(){
	$('#start_time,#end_time').datepicker({
		dateFormat: "yy/mm/dd"
	});
});

var saveUrl = '<?=site_url('backend/save');?>';
var articleUrl = '<?=site_url('backend/article');?>';
</script>

<div ng-controller="articleController">
<form id="form" name="form" class="css-form" novalidate style="width:300px;">
	<input type="hidden" name="table" value="article_info"/>
	<div class="input-group">
		<span class="input-group-addon">文章id：</span>
		<input type="text" class="form-control" id="post_id" ng-model="post_id" name="post_id" numbers-only="numbers-only" required/>
	</div>
	<br/>
	<div class="input-group">
		<span class="input-group-addon">文章標題：</span>
		<input type="text" class="form-control" id="title" ng-model="title" name="title" required/>
	</div>
	<br/>
	<div class="input-group">
		<span class="input-group-addon">開始時間：</span>
		<input type="text" class="form-control" id="start_time" ng-model="start_time" name="start_time" required/>
	</div>
	<br/>
	<div class="input-group">
		<span class="input-group-addon">結束時間：</span>
		<input type="text" class="form-control" id="end_time" ng-model="end_time" name="end_time" required/>
	</div>
</form>
<br/>
<button type="button" class="btn btn-default" ng-click="submit_();"><span class="glyphicon glyphicon-save"></span>送出</button>
<button type="button" class="btn btn-default" ng-click="back_();"><span class="glyphicon glyphicon-remove"></span>取消</button>
</div>
