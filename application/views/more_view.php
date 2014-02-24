
<script>

var backurl = '<?= site_url('main/result') ?>';
var moreurl = '<?= site_url('main/more') ?>';

var articles = <?=json_encode($articles);?>;

$(function(){
	
});
</script>

<div ng-app="myApp" ng-controller="moreController">
	<button ng-click="back_();">回到積點頁</button>
	<div>
		<div ng-repeat="ar in articles" sn="{{ $index }}">
			<div><a href="//www.facebook.com/{{ar.post_id}}" target="_blank">{{ar.title}}</a></div>
		</div>
	</div>

</div>
