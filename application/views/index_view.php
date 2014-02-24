<div ng-controller="indexCtrl">

<form id="baby_form" method="POST" action="<?=site_url('main/result');?>">
  <input type="text" placeholder="請輸入寶寶名字(或綽號)" ng-model="babyname" id="babyname"/>
</form>
<button ng-click="login(0)">開始製作</button>
<button ng-click="login(1)">直接登入</button>
</div>