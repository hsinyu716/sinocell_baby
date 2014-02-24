<?php
class copywrite
{
  /*
   * variable declarations
  */
  public function getCopywrite(){
      $list = array(
      		array(
      				'{User1}, 在  {地點} 跟 , {User2}, 拿著黑松汽水 {事件}',
      				array(
      						3=>'3,做一字馬劈腿,5,6',
      						5=>'5,把大提琴當吉他邊彈邊唱,9,10',
      						7=>'7,邊跳繩邊後空翻,13,14',
      						8=>'8,被進食的巨人吞食,15,16',
      					)
      		),
      		array(
      				'{User1}, 在  {地點} 跟 , {User2}, 拿著沁涼香檳 {事件}',
      				array(
      						1=>'1,玩蒙古式摔角PK,1,2',
      						2=>'2,大跳熱舞加地板動作,3,4',
      						4=>'4,玩七龍珠合體技,7,8',
      						6=>'6,跳騎馬舞,11,12'	
      					)
      		),
      		array(
      				'自由廣場',
      				'便利商店',
      				'馬戲團',
      				'海水浴場',
      				'嘉年華會',
      				'摩天輪',
      				'KTV包廂',
      				'電影院',
      		)
          );
      return $list;
  }
 
} //class ends here
?>
