<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * Author : Hsinyu
 * Email   : z2493225@gmail.com
 */
 
 /**
  * [getAge 計算年紀]
  * @param  [type] $birth [description]
  * @return [type]        [description]
  */
function getAge($birthDate){

    //explode the date to get month, day and year
    // $birthDate = explode("-", $birthDate);
    // //get age from date or birthdate
    // $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[0], $birthDate[1], $birthDate[2]))) > date("md")
    // ? ((date("Y") - $birthDate[0]) - 1)
    // : (date("Y") - $birthDate[0]));
    // return $age;
    // 
    $date = new DateTime($birthDate);
    $now = new DateTime();
    $interval = $now->diff($date);

    // var_dump($interval->y,$interval->m,$interval->d);

    if(strtotime($birthDate) > time()):
        if($interval->d>15):
            $interval->m += 1;
        endif;
        $data = array(
            'status' => 0,
            'month' => $interval->m,
            'year' => $interval->y
            );
    else:
        if($interval->d>15):
            $interval->m += 1;
        endif;
        $data = array(
            'status' => 1,
            'month' => $interval->m,
            'year' => $interval->y
            );
    endif;
    return $data;
}

/* End of file age_helper.php */
/* Location: ./application/helpers/age_helper.php */

