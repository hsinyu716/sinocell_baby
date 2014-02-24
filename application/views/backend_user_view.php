<link rel="stylesheet" type="text/css" href="js/grid/css/flexigrid.css">
<script type="text/javascript" src="js/grid/js/flexigrid.js"></script>

<table class="main_div" id="flex1" style="display:none;"></table>
<script type="text/javascript">

var sortAlpha=function(com){
	location.href="<?=site_url('backend/output');?>";
};

function edit(o){
	location.href="<?=site_url('backend/editquestionna');?>/"+o;
}

var main = function(){
	return{
        init : function(){            
        	$("#flex1").flexigrid({
        		method: 'POST',
        		url: '<?=site_url('backend/getAjaxList')?>',
        		params:[{
            		'name':'table',
            		'value':'user_info'
        		}],
        		dataType: 'json',
        		colModel : [
        			{display: 'ID', name : 'serial_id', width : 120, sortable : true, align: 'center'},
        			{display: 'fbid', name : 'fbid', width : 120, sortable : true, align: 'center'},
        			{display: 'fbname', name : 'fbname', width : 120, sortable : true, align: 'center'},
        			{display: '姓名', name : 'username', width : 100, sortable : true, align: 'center'},
        			{display: '電話', name : 'tel', width : 230, sortable : true, align: 'center'},
        			{display: 'email', name : 'email', width : 120, sortable : false, align: 'center'}
        			],

        		searchitems : [
        			{display: 'fbid', name : 'fbid', isdefault: true},
        			],
        		sortname: "ID",
        		sortorder: "asc",
        		usepager: true,
        		resizable:false,
        		title: '使用者列表',
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
					{separator: true},
					{name: '匯出資料', bclass: 'add', onpress : sortAlpha},
					{separator: true},
				], 
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



