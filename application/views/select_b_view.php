<script>
var self_ = "<?=site_url('main/select');?>";
var rand_ = "<?=site_url('main/result');?>";
</script>
<form id="fri_form" action="<?=site_url('main/result');?>" method="POST">
<input type="hidden" name="frie[]" value="0"/>
</form>

<div id="top" ng-app="myApp" ng-controller="selectController">
	<button ng-click="confirm(0);">自選朋友</button><button ng-click="confirm(1);">隨機選朋友</button>
</div>
