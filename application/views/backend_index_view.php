<script type='text/javascript'>
	
    var a = <?= $admin['a'] ?>;
    var checkboxs = new Array();
    var answers = new Array();
	
    function check_box(){
        for(j=1;j<=<?= limit_answer_min ?>;j++){		
            if($('#answer'+j).val() == ''){
                alert('請輸入選項 '+j);
                return false;
            }			
        }

        for(j=<?= limit_answer_max ?>;j>=<?= limit_answer_min + 2 ?>;j--){
            if( $("#checkbox"+j).attr('checked') && !$("#checkbox"+(j-1)).attr('checked') ){
                alert('選項 ' + (j-1) + ' 未勾選, 選項 ' + j + ' 不能勾選.');
                return false;
            }				
        }
		
        for(j=<?= limit_answer_min + 1 ?>;j<=<?= limit_answer_max ?>;j++){						
            if($("#checkbox"+j).attr('checked') && $('#answer'+j).val() == ''){
                alert('選項 '+j+" 有勾選, 其內容不能空白.");
                return false;
            }				
        }		

        for(j=1;j<=<?= limit_answer_max ?>;j++){		
            checkboxs[j] = $("#checkbox"+j).attr('checked') ? 'Y' : 'N' ;
            answers[j] = $("#answer"+j).val();
        }		

        for(j=1;j<=<?= limit_answer_min ?>;j++){		
            checkboxs[j] = 'Y';
        }	
		
        return true;
    }
	
    function check_radio(){
        $checked = false;
		
        for(j=1;j<=<?= limit_answer_max ?>;j++){
            if( $("#radio"+j).attr('checked') ){
                $checked = true;
                a = j;
                break;
            }			
        }		
		
        if(!$checked){
            alert('請選擇答案.');
            return false;
        }
		
        for(j=<?= limit_answer_min + 1 ?>;j<=<?= limit_answer_max ?>;j++){						
            if( !$("#checkbox"+j).attr('checked') && $("#radio"+j).attr('checked') ){
                alert('選項 '+j+' 沒有被勾選, 不能當做答案.');
                return false;				
            }
        }						
        return true;		
    }	
	
    function publish(preview){	
        preview = typeof preview !== 'undefined' ? preview : false;		

        if($('#youtube').val() == ''){
            alert('請輸入Youtube 影片 Code');
            return false;
        }

        if($('#q').val() == ''){
            alert('請輸入問題');
            return false;
        }
			
        if(!check_box()){
            return false;
        }

        if(!check_radio()){
            return false;			
        }		
        poploading();
        $.ajax({
            type:"POST", 
            url:"<? WEB_HOST ?>index.php/backend/ajax_update_index/"+preview,
            dataType:'json',
            data:{
                'q': $("#q").val(),
                'a': a,
                'youtube': $("#youtube").val(),				
                'fill_1': $("#fill_1").val(),
                'fill_2': $("#fill_2").val(),
                'fill_3': $("#fill_3").val(),
                'checkboxs': checkboxs,
                'answers': answers				
            }, 
            success:function(resp){
                if(Boolean(resp.success)){					
                    if(preview){						
                        showpreview();						
                    }else{
                        alert('Done.');						
                    }				
                }else{
                    alertError();
                }
            },
            error:function(){
                alertError();
            },			
            complete: function() {	
                closeloading();
            }	
        });			
		
    }

	
</script>

<div style='background-color:pink;'>
    <center>
        <div><h1>輸入Youtube 影片 Code</h1></div>	
        <div>http://www.youtube.com/watch?v=<input type='text' id='youtube' value='<?= $admin['youtube'] ?>' /></div>
    </center>
</div>
<br />
<div style='background-color:yellow;'>
    <center>
        <div><h1>輸入問答</h1></div>	
        <div><font color='red'>＊</font>題目：<textarea cols="100" rows="5"  id='q'><?= $admin['q'] ?></textarea></div>
        <div>選項：(請勾選欲顯示的問題選項, 至少<?= limit_answer_min ?>題,最多<?= limit_answer_max ?>題)
            <?php
            for ($j = 1; $j <= limit_answer_min; $j++) {
                echo "<div><font color='red'>＊</font>選項 $j ：<input type='text' id='answer$j' value='" . $answers[$j]['answer'] . "' /></div>";
            }
            for ($j = (limit_answer_min + 1); $j <= limit_answer_max; $j++) {
                $checked = '';
                if ($answers[$j]['used'] == 'Y') {
                    $checked = 'checked';
                }
                echo "<div><input type='checkbox' id='checkbox$j' $checked />選項 $j ：<input type='text' id='answer$j' value='" . $answers[$j]['answer'] . "' /></div>";
            }
            ?>
            <div>
                <div><font color='red'>＊</font>答案：(上述的選項<?= limit_answer_min + 1 ?>~<?= limit_answer_max ?>有被勾選下列對應的<?= limit_answer_min + 1 ?>~<?= limit_answer_max ?>才有效)</div>
                <div>
                    <?php
                    for ($j = 1; $j <= limit_answer_max; $j++) {
                        $checked = '';
                        if ($admin['a'] == $j) {
                            $checked = 'checked';
                        }
                        echo "<input type='radio' id='radio$j' name='answer' value='$j' $checked />選項 $j ";
                    }
                    ?>
                </div>
            </div>
    </center>
</div>
<br />
<div style='background-color:green;'>
    <center>
        <div>填寫基本資料並送出，<input size="100" type='text' id='fill_1' value='<?= $admin['fill_1'] ?>' /></div>	
        <div>標題：<input type='text' id='fill_2' value='<?= $admin['fill_2'] ?>' /></div>
        <div>姓名：<input type='text' disabled /></div>
        <div>電話：<input type='text' disabled /></div>
        <div>地址：<input type='text' disabled /></div>
        <div>email：<input type='text' disabled /></div>
        <div>說明文字：<textarea cols="100" rows="1" id='fill_3' /><?= $admin['fill_3'] ?></textarea></div>
    </center>
</div>
<br />
<div>
    <center>
        <div>
            <input type='button' value='　預　覽　' onclick='publish("preview")' />
            <input type='button' value='　發　佈　' onclick='publish()' />
        </div>	
    </center>
</div>