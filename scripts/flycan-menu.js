// 飛肯設計學苑 http://www.flycan.com.tw/

$(function(){
	menu(".hs_btn",800,1000,10);
	menu(".hs_share",800,1000,10);
	menu(".hs_info",800,1000,10);
});

function menu(obj,period1,period2,ext){

	$(obj).hover(function(){
		$(this).stop(true,true).animate({top:"-=" + ext},period1,"easeOutBounce");
	},function(){
		$(this).stop(true,true).animate({top:"+=" + ext},period2,"easeOutBack");
	});
	
}