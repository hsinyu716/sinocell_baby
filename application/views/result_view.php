<script src="<?= WEB_HOST ?>js/angularjs/angular-file-upload.js"></script>
<script type="text/javascript" src="<?= WEB_HOST ?>js/angularjs/resultCtrl.js" ></script>

<script>

var posturl = '<?= site_url('main/po_wall') ?>';
var moreurl = '<?= site_url('main/ajax_list') ?>';
var editurl = '<?= site_url('main/edit') ?>';
var jointurl = '<?= site_url('main/joint') ?>';
var msgurl = '<?= site_url('main/set_message') ?>';
var dmsgurl = '<?= site_url('main/del_message') ?>';
var listurl = '<?= site_url('main/get_message') ?>';
var resulturl = '<?= site_url('main/result') ?>';
var uploadurl = '<?= site_url('file_main/do_upload') ?>';
var gallaryurl = '<?= site_url('main/index') ?>';
var setpicurl = '<?= site_url('main/set_pic') ?>';
var addfriendurl = '<?= site_url('main/add_friend') ?>';
var getfriendurl = '<?= site_url('main/getfriend') ?>';

var user = <?= json_encode($user);?>;
var msg = <?= json_encode($msg);?>;
var rank = <?= json_encode($rank);?>;
var friends = <?= json_encode($friends);?>;
var mypic = 'img/a.jpg';
var is_view = '<?=$is_view?>';
var is_friend = '<?=$is_friend;?>';


$(function(){
	if(user.sex!=null){
		$('#'+user.sex).prop('checked',true);
	}

	if(user.path!=null){
		mypic = user.path;
	}
	angular.element(document.getElementById('angularobj')).scope().$apply(function(scope){
        scope.setmypic(mypic);
    });

	if(user.is_update=='Y'){
		
	}

});
</script>

<div id="angularobj" ng-app="myApp" ng-controller="resultCtrl">

<div>
	<a href="<?=site_url('main/view');?>/{{user.serial_id}}">
		<img ng-src="{{ mypic }}" />
		<span>{{ user.babyname }}</span>
	</a>
	<button ng-click="gallery()" ng-show="is_view==='false'">圖庫</button>
	<button ng-click="add_friend(user.serial_id)" ng-show="is_friend==='false'">加好友</button>
</div>

<div id="gallery_div" style="display:none;">
	<img src="img/a.jpg" ng-click="pick_pic('img/a.jpg');"/>
	<img src="img/b.jpg" ng-click="pick_pic('img/b.jpg');"/>
	<img src="img/c.jpg" ng-click="pick_pic('img/c.jpg');"/>
	<img src="img/d.jpg" ng-click="pick_pic('img/d.jpg');"/>
	<img src="img/e.jpg" ng-click="pick_pic('img/e.jpg');"/>
	<img src="img/f.jpg" ng-click="pick_pic('img/f.jpg');"/>
</div>

<input type="file" style="opacity: 1;" ng-file-select="onFileSelect($files)" ng-show="is_view==='false'">

<button ng-click="my_view()" ng-show="is_view==='true'">回我的頁面</button>

<div ng-show="is_view==='false'">
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

<?if($is_view==='true'):?>
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
<button ng-click="open_(2)">瀏覽更多</button>
</div>

<!-- popup列表 -->
<div id="list_div" style="display:none;background:#fff;">
	<input type="text" ng-model="searchText" placeholder="請輸入寶寶姓名"/><button ng-click="search_()">搜尋</button>
	<div ng-repeat="l in lists" sn="{{ $index }}">
		<div>{{ l.babyname }}</div>
	</div>
	<pagination on-select-page="pageChanged(page)" items-per-page="{{numPerPage}}" total-items="bigTotalItems" page="bigCurrentPage" max-size="maxSize" class="pagination-sm" previous-text="上一頁" next-text="下一頁" first-text="&laquo;" last-text="&raquo;"></pagination>
</div>

<!-- 右半邊(資料更新才顯示) -->
<div id="detail" ng-show="user.is_update=='Y'">
	我寶寶的朋友<span id="fricnt" ng-click="open_(1)">{{friends.length}}</span>
	<button ng-click="apprequests(3)" ng-show="user.friends_cnt==0 && is_view==='false'">趕快幫寶寶交朋友</button>

	<!-- 留言 -->
	<div>
	留言給我
	</div>
	<textarea placeholder="留言" ng-model="message"></textarea>
	<button ng-click="set_message();">送出</button>

	<div ng-repeat="m in msg" sn="{{ $index }}">
		<a href="<?=site_url('main/view');?>/{{m.baby_serial}}">
			<img src="img/a.jpg" ng-show="m.photo == NULL"/>
			<img src="{{m.photo}}" ng-show="m.photo != NULL" />
		</a>
		<div>
			<span>
				<a href="<?=site_url('main/view');?>/{{m.baby_serial}}">{{ m.babyname }}</a> → 
			</span>{{user.babyname}}
		</div><button ng-click="confirm('是否刪除','delmsg',$index);" ng-show="is_view==='false'">刪除</button>
		{{ m.message }}
	</div>
</div>



</div> 