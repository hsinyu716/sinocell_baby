<script>
var articles = <?= json_encode($articles);?>;
var article = null;
var createUrl = '<?=site_url('backend/createArticle');?>';
var editUrl = '<?=site_url('backend/editArticle');?>';

</script>
<link rel="stylesheet" type="text/css" href="js/grid/css/flexigrid.css">
<script type="text/javascript" src="js/grid/js/flexigrid.js"></script>

<div ng-controller="articleController">
<button type="button" class="btn btn-default" ng-click="create_();"><span class="glyphicon glyphicon-plus"></span>新增文章</button>
<table class="main_div" id="flex1" style="display:none;"></table>

</div>
<script type="text/javascript">

var sortAlpha=function(com){
	location.href="<?=site_url('backend/output');?>";
};

function edit_(o){
	location.href=editUrl+'/'+o;
}

var main = function(){
	return{
        init : function(){            
        	$("#flex1").flexigrid({
        		method: 'POST',
        		url: '<?=site_url('backend/getAjaxList')?>',
        		params:[{
            		'name':'table',
            		'value':'article_info'
        		}],
        		dataType: 'json',
        		colModel : [
        			{display: 'ID', name : 'serial_id', width : 120, sortable : true, align: 'center'},
        			{display: '文章id', name : 'post_id', width : 120, sortable : true, align: 'center'},
        			{display: '文章標題', name : 'title', width : 320, sortable : true, align: 'center'},
        			{display: '開始時間', name : 'start_time', width : 120, sortable : true, align: 'center'},
        			{display: '結束時間', name : 'end_time', width : 100, sortable : true, align: 'center'},
        			{display: '操作', name : 'operating', width : 100, sortable : true, align: 'center'},
        			],

        		searchitems : [
        			{display: 'fbid', name : 'fbid', isdefault: true},
        			],
        		sortname: "ID",
        		sortorder: "asc",
        		usepager: true,
        		resizable:false,
        		title: '文章列表',
        		useRp: true,
        		rp: 15,
        		showTableToggleBtn: true,
        		width: 'auto',
        		//onSubmit: addFormData,
        		height: 450,
        		procmsg: '資料讀取中,請稍後 ...',
				nomsg: '沒有符合條件的資料',
				singleSelect:true,
				//nohresize:true,
				//striped:true,
				pagestat : '顯示第 {from}筆 到 第{to}筆 資料,共{total}筆資料',
				buttons : [
				]
        	});
        },
        load : function(){
        	$("#flex1").flexReload(); 	
        },
        edit_row:function(eid){
            location.href="<?=site_url('backend/editquestionna')?>/"+eid;
        },
        edit_score:function(eid){
            location.href="<?=site_url('backend/scorelist')?>/"+eid;
        },
        del_row:function(sid){
        	if(confirm('確定刪除？')){
                 var params = {'sid' : sid};
                 $.post('<?=site_url('backend/delete_q')?>',params, function(res) {
                     if(res.success)
                     {
                         alert('刪除成功!');
                         main.load();
                     }else
                     {
                    	 alert('刪除失敗!，請聯絡管理員!');
                     }                                    
                 },'json');
            }
        }
	}
}();
$(function(){
	main.init();
});

</script>