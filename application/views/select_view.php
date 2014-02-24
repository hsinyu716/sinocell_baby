<script src="js/multi/jquery.facebook.multifriend.select.js" type="text/javascript"></script>
<link rel="stylesheet" href="js/multi/jquery.facebook.multifriend.select.css" type="text/css" />
<style type="text/css">
#jfmfs-container{
	background: #fff;
}

#jfmfs-friend-selector{
    height: 290px;
    overflow-y: hidden;
    width: 595px;
}

#jfmfs-friend-container{
    height: 255px;
}


.jfmfs-friend{
    width:119px;
}

.jfmfs-friends {                
    cursor:pointer;
    display:block;
    float:left;
    height:56px;
    margin:3px;
    padding:4px;
    width:120px;
    border: 1px solid #FFFFFF;
    -moz-border-radius: 5px; 
    -webkit-border-radius: 5px;
    -webkit-user-select:none;
    -moz-user-select:none;
    overflow:hidden;
}

.jfmfs-friends img {
    border: 1px solid #CCC;
    float:left;
    margin:0;
}

.jfmfs-friends.selected img {
    border: 1px solid #233E75;
}

.jfmfs-friends div {
    color:#111111;
    font-size:11px;
    overflow:hidden;
    padding:2px 0 0 6px;
}
#jfmfs-friend-selector input[type="text"]{
    margin-left:100px;
}
.selected {
    background-color: #ff7a00 !important;
    border: 1px solid #ff7a00 !important;
    
    background: #ff891e !important; /* for non-css3 browsers */

    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ea6f00', endColorstr='#ff8e28') !important; /* for IE */
    background: -webkit-gradient(linear, left top, left bottom, from(#ea6f00), to(#ff8e28)) !important; /* for webkit browsers */
    background: -moz-linear-gradient(top,  #ea6f00, #ff8e28) !important; /* for firefox 3.6+ */    
}
.selected.selected div{
color:#fff !important;
}

</style>
<script>
var posi = <?= $position;?>;

var everAppended = false;
var pit = 0;

</script>

<form id="fri_form" action="<?=site_url('main/result');?>" method="POST">
<input type="hidden" name="frie[]" value="0"/>
<input type="hidden" name="frie[]" value="0"/>
<input type="hidden" name="frie[]" value="0"/>
<input type="hidden" name="frie[]" value="0"/>
<input type="hidden" name="frie[]" value="0"/>
<input type="hidden" name="frie[]" value="0"/>
<input type="hidden" name="frie[]" value="0"/>
<input type="hidden" name="frie[]" value="0"/>
<input type="hidden" name="frie[]" value="0"/>
</form>

<div id="top" ng-app="myApp" ng-controller="friendController">

<div id="friend_div2" class="radius" style="width:735px;height:500px;text-align:center;">
	<div id="jfmfs-container" style="height:450px;overflow:auto;">
	<?php foreach($friends as $fk=>$fv):?>
		<div class='jfmfs-friend' id='<?=$fv['uid'];?>' ng-click="selectf(<?=$fv['uid'];?>);"><img src="//graph.facebook.com/<?=$fv['uid'];?>/picture"/><div class='friend-name'><?=$fv['name'];?></div></div>
	<?php endforeach;?>
	</div>
	<button  style="width:100px;height:45px;" ng-click="conf_sel();">確定</button>
</div>

	<div ng-repeat="work in works" sn="{{ $index }}">
		<div>
			<div id="fri{{ $index }}"></div>
			{{ work }}<button ng-click="select($index)">選朋友</button>
		</div>
	</div>
	
	<button ng-click="confirm(0);">送出</button>
	
</div>

