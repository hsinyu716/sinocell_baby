<script type="text/javascript" src="<?= WEB_HOST ?>js/angularjs/resultCtrl.js" ></script>

<script>

var posturl = '<?= site_url('main/po_wall') ?>';
var setDataurl = '<?= site_url('main/setData') ?>';
var refreshurl = '<?= site_url('main/ajax_point') ?>/true';
var shareurl = '<?= site_url('main/share') ?>';
var moreurl = '<?= site_url('main/more') ?>';
var editurl = '<?= site_url('main/edit') ?>';
var jointurl = '<?= site_url('main/joint') ?>';
var msgurl = '<?= site_url('main/set_message') ?>';
var listurl = '<?= site_url('main/get_message') ?>';
var resulturl = '<?= site_url('main/result') ?>';

var user = <?= json_encode($user);?>;
var msg = <?= json_encode($msg);?>;
var rank = <?= json_encode($rank);?>;
var friends = <?= json_encode($friends);?>;

$(function(){
	if(user.sex!=null){
		$('#'+user.sex).prop('checked',true);
	}

	if(user.is_update=='Y'){
		
	}

});
</script>

<div ng-app="myApp" ng-controller="resultCtrl">

<div>
	<a href="<?=site_url('main/view');?>/{{user.serial_id}}">
		<img src="img/a.jpg" ng-show="user.file_id == NULL"/>
		<span>{{ user.babyname }}</span>
	</a>
	<button ng-click="pick_photo()" ng-show="<?=!$is_view;?>">照片</button>
	<button ng-click="add_friend()" ng-show="<?=!$is_friend;?>">加好友</button>
</div>

<button ng-click="my_view()" ng-show="<?=$is_view;?>">回我的頁面</button>

<div ng-show="<?=!$is_view;?>">
	<button ng-click="req_joint();" ng-show="user.is_joint=='N'">邀請</button>
	<form id="userForm" name="userForm" novalidate>
		<input type="hidden" name="serial_id" value="{{user.serial_id}}" />
		<input type="text" id="daddy" name="daddy" value="{{user.daddy}}" placeholder="爸爸名字" ng-model="user.daddy" required>
		<input type="text" id="mom" name="mom" value="{{user.mom}}" placeholder="媽媽名字" ng-model="user.mom" required>
		<input type="text" id="babybirthday" name="babybirthday" datepicker-popup="{{format}}" ng-model="user.babybirthday" show-button-bar="false" is-open="opened" min="minDate" datepicker-options="dateOptions" date-disabled="disabled(date, mode)" ng-required="true" close-text="Close" />
		<input type="radio" name="sex" id="female" value="female" ng-click="sexchange('female');"/>
		<input type="radio" name="sex" id="male" value="male" ng-click="sexchange('male');"/>
		<input type="hidden" name="babysex" value="{{user.sex}}" ng-model="user.sex"/>
		<button ng-click="edit_check()" ng-disabled="userForm.$invalid" style="border:0;background:none;"><img src="img/btn3.png" /></button>
	</form>
</div>

<?if($is_view):?>
<div>
爸爸名字:{{user.daddy}}
媽媽名字:{{user.mom}}
寶寶生日:<span ng-show="<?=$age['status']?>==0"><?=$age['month']?>後出生</span><span ng-show="<?=$age['status']?>==1"><?=$age['year']?>歲<?=$age['month']?>個月</span>
寶寶性別:{{user.sex}}
</div>
<?endif;?>

<!-- 排行榜 -->
<div style="border:1px solid #ccc;">
排行榜
	<div ng-repeat="r in rank" sn="{{ $index }}">
		<div>{{ r.babyname }}</div>
	</div>
</div>

<!-- 列表 -->
<div id="list_div" style="display:none;">
	<div ng-repeat="l in lists" sn="{{ $index }}">
		<div>{{ l.babyname }}</div>
	</div>
	<pagination on-select-page="pageChanged(page)" items-per-page="{{numPerPage}}" total-items="bigTotalItems" page="bigCurrentPage" max-size="maxSize" class="pagination-sm" previous-text="上一頁" next-text="下一頁" first-text="&laquo;" last-text="&raquo;"></pagination>
</div>

<!-- 右半邊(資料更新才顯示) -->
<div id="detail" ng-show="user.is_update=='Y'">
	我朋友的寶寶<span id="fricnt" ng-click="open_(1)">{{user.friends_cnt}}</span>
	<button ng-click="apprequests(3)" ng-show="user.friends_cnt==0 && <?=$is_view;?>">趕快幫寶寶交朋友</button>

	<!-- 留言 -->
	<div>
	留言給我
	</div>
	<textarea placeholder="留言" ng-model="message"></textarea>
	<button ng-click="set_message();">送出</button>

	<div ng-repeat="m in msg" sn="{{ $index }}">
		<a href="<?=site_url('main/view');?>/{{m.baby_serial}}">
			<img src="img/a.jpg" ng-show="m.file_id == NULL"/>
		</a>
		<div>
			<span>
				<a href="<?=site_url('main/view');?>/{{m.baby_serial}}">{{ m.babyname }}</a> → 
			</span>{{user.babyname}}
		</div>
		{{ m.message }}
	</div>
</div>





</div> 