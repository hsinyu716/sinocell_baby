<script>
var prizes = <?= json_encode($prizes);?>;
var createUrl = '<?=site_url('backend/createPrize');?>';
var editUrl = '<?=site_url('backend/editPrize');?>';
var point = 0;

$(function() {
});
</script>
<style>
.prizes{
 	text-align:center;
 	float:left;
 }

.prizes img{
	height:200px !important;
}

#sortable li {
	list-style: none;
}
</style>

<div ng-controller="prizeController">
	<button type="button" class="btn btn-default" ng-click="create_();"><span class="glyphicon glyphicon-plus"></span>新增獎品</button>
	
		<ul id="sortable">
		<li ng-repeat="pr in prizes" sn="{{ $index }}" >
			<div class="prizes">
				<img src="{{ pr.img }}"/><br/>兌換點數：{{pr.point}}
				<div>
				<button type="button" class="btn" ng-click="edit_(pr.serial_id);"><span class="glyphicon glyphicon-pencil"></span>編輯</button>
				</div>
			</div>
		</li>
		</ul>
</div>