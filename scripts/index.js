var p_btn_go = 500;
$(document).ready(function() {
	initial();

/*$('.btn_send').hover(
	function(){
		$('.btn_send .g1').animate({
		top: "-=" + 5,
		opacity:0
		},100);
		
		$('.btn_send .g2').animate({
		top: "+=" + 5,
		opacity:1
		},100);
		
	},
	function(){
		$('.btn_send .g1').animate({
		top: "+=" + 5,
		opacity:1
		},100);
		
		$('.btn_send .g2').animate({
		top: "-=" + 5,
		opacity:0
		},100);
		
	}
);*/

});
function initial(){
	//setTimeout("btn_go()",p_btn_go);
	$(".hs_pattern").css("top","-=20" + "px");
	$(".hs_pattern").css("opacity",0);
	$(".hs_pattern")
	.animate({top:"+=30",opacity:1},350)
	.animate({top:"-=10"},150);

	$(".hs_text").css("left","-=30" + "px");
	$(".hs_text").css("opacity",0);
	$(".hs_text")
	.animate({marginLeft:"40px",opacity:1},450)
	.animate({marginLeft:"-=30px"},250);
	
	$(".hs_btn").css("top","-=20" + "px");
	$(".hs_btn").css("opacity",0);
	$(".hs_btn")
	.animate({top:"+=10",opacity:1},350)
	.animate({top:"-=10"},150);
	
	
	
	btn_go();
}

function btn_go(){
	//log("btn_go");

	setTimeout(change_class,100);

}

var ai = 0;
var speed = 400;

 
function change_class(){
	$("._big_container .middle .bubble .btn_go img")
	.animate({width:423},200)
	.animate({width:428},200,function(){change_class()});
	
/*	$("._big_container .middle .bubble .btn_go")
	.css("background-position", ai*428 + "px 0px");

	log("change class");
	if(ai<1)
	ai++;
	else ai=0;
	setTimeout(function(){
		change_class();
	},speed);*/
}

function log(txt){
	console.log(txt);
}