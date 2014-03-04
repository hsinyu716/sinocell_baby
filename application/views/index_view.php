<script type="text/javascript" src="<?= WEB_HOST ?>js/angularjs/indexCtrl.js" ></script>
<script>
var fb_scope = '<?=SCOPE;?>';
var checkuserurl = "<?= site_url('main/check_user') ?>";
var resulturl = "<?=site_url('main/result');?>";

</script>

<div id="indexobj" ng-controller="indexCtrl">

<form id="baby_form" method="POST" action="<?=site_url('main/result');?>">
  <input type="text" placeholder="請輸入寶寶名字(或綽號)" ng-model="babyname" name="babyname"/>
</form>
<button ng-click="login(0)">開始製作</button>
<button ng-click="login(1)">直接登入</button>

</div>