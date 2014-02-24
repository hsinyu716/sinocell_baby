// 飛肯設計學苑 http://www.flycan.com.tw/

$(document).ready(function() {
		$('.mj_show').on('mouseover',function(){
			$('.mj_text')
				.hide() // hide it
				.html( $(this).data('text') ) // change mj_text's text 
				.fadeIn('fast'); // and fadeIn mj_text
		});
		
		
	});