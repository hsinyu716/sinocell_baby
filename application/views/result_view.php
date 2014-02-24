
<script>

var posturl = '<?= site_url('main/po_wall') ?>';
var setDataurl = '<?= site_url('main/setData') ?>';
var refreshurl = '<?= site_url('main/ajax_point') ?>/true';
var shareurl = '<?= site_url('main/share') ?>';
var moreurl = '<?= site_url('main/more') ?>';
var editurl = '<?= site_url('main/edit') ?>';


var user = <?= json_encode($user);?>;

$(function(){
	$('#babybirthday').datepicker({
		dateFormat: "yy/mm/dd"
	});

	if(user.sex!=null){
		$('#'+user.sex).prop('checked',true);
	}

	if(user.is_update=='Y'){
		alert(1);
	}

});
</script>

<div ng-app="myApp" ng-controller="resultCtrl">

<form id="baby_form" novalidate>
<input type="hidden" name="serial_id" value="{{user.serial_id}}" />
<input type="text" id="daddy" name="daddy" value="{{user.daddy}}" placeholder="爸爸名字" ng-model="user.daddy" required>
<input type="text" id="mom" name="mom" value="{{user.mom}}" placeholder="媽媽名字" ng-model="user.mom" required>
<div ng-show="baby_form.mom.$dirty">Invalid:
        <span ng-show="baby_form.mom.$error.required">Tell us your email.</span>
      </div>
<input type="text" id="babybirthday" name="babybirthday" value="{{user.babybirthday}}" placeholder="寶寶生日" ng-model="user.babybirthday" required>
<input type="radio" name="sex" id="female" value="female" ng-click="sexchange('female');"/>
<input type="radio" name="sex" id="male" value="male" ng-click="sexchange('male');"/>
<input type="hidden" name="babysex" value="{{user.sex}}" ng-model="user.sex"/>
</form>
<button ng-click="edit_check()">送出</button>



</div>
